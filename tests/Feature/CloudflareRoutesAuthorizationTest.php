<?php

namespace Tests\Feature;

use Laravel\Nova\Http\Middleware\Authenticate;
use Laravel\Nova\Http\Middleware\Authorize as NovaAuthorize;
use Orchestra\Testbench\Attributes\WithConfig;
use Tests\Fixtures\RejectRequest;
use Tests\TestCase;
use ZiffMedia\LaravelCloudflare\Cloudflare;
use ZiffMedia\LaravelCloudflare\Nova\Tools\LaravelCloudflareTool;

class CloudflareRoutesAuthorizationTest extends TestCase
{
    public const PURGE_URI = '/nova-vendor/laravel-cloudflare/purge';

    public const PURGE = ['urls' => ['https://example.com/'], 'tags' => ['articles-1']];

    public function test_guests_are_rejected(): void
    {
        $this->postJson(self::PURGE_URI, self::PURGE)->assertUnauthorized();
    }

    public function test_users_who_fail_the_view_nova_gate_are_forbidden(): void
    {
        $this->actingAs($this->novaUser(viewNova: false))->postJson(self::PURGE_URI, self::PURGE)->assertForbidden();
    }

    public function test_the_tool_can_see_check_decides_who_gets_in(): void
    {
        $this->tools = [LaravelCloudflareTool::make()->canSee(fn ($request) => $request->user()->getAuthIdentifier() === 2)];
        $cloudflare = $this->spy(Cloudflare::class);

        $this->actingAs($this->novaUser())->postJson(self::PURGE_URI, self::PURGE)->assertForbidden();
        $cloudflare->shouldNotHaveReceived('purgeCacheByUrls');
        $cloudflare->shouldNotHaveReceived('setTags');

        $this->actingAs($this->novaUser()->forceFill(['id' => 2]))->postJson(self::PURGE_URI)->assertOk();
    }

    public function test_the_route_is_not_found_when_the_app_does_not_register_the_tool(): void
    {
        $this->tools = [];
        $cloudflare = $this->spy(Cloudflare::class);

        $this->actingAs($this->novaUser())->postJson(self::PURGE_URI, self::PURGE)->assertNotFound();
        $cloudflare->shouldNotHaveReceived('purgeCacheByUrls');
        $cloudflare->shouldNotHaveReceived('setTags');
    }

    public function test_the_login_and_view_nova_checks_run_before_the_tool_lookup(): void
    {
        $this->tools = [];

        $this->postJson(self::PURGE_URI, self::PURGE)->assertUnauthorized();
        $this->actingAs($this->novaUser(viewNova: false))->postJson(self::PURGE_URI, self::PURGE)->assertForbidden();
    }

    #[WithConfig('nova.api_middleware', ['nova', Authenticate::class, NovaAuthorize::class, RejectRequest::class], defer: false)]
    public function test_the_route_uses_the_apps_nova_api_middleware(): void
    {
        $this->actingAs($this->novaUser())->postJson(self::PURGE_URI, self::PURGE)->assertStatus(418);
    }

    #[WithConfig('nova.domain', 'cms.test', defer: false)]
    public function test_the_route_only_answers_on_the_nova_domain(): void
    {
        $this->actingAs($this->novaUser());

        $this->postJson('http://localhost' . self::PURGE_URI)->assertNotFound();
        $this->postJson('http://cms.test' . self::PURGE_URI)->assertOk();
    }

    public function test_allowed_users_can_purge(): void
    {
        $cloudflare = $this->spy(Cloudflare::class);
        $cloudflare->shouldReceive('setTags')->andReturnSelf();

        $this->actingAs($this->novaUser())
            ->postJson(self::PURGE_URI, self::PURGE)
            ->assertOk()
            ->assertExactJson(['purgedUrls' => ['https://example.com/'], 'purgedTags' => ['articles-1'], 'errorMessage' => null]);

        $cloudflare->shouldHaveReceived('purgeCacheByUrls')->once()->with(['https://example.com/']);
        $cloudflare->shouldHaveReceived('setTags')->once()->with(['articles-1']);
        $cloudflare->shouldHaveReceived('purgeCacheByTags')->once();
    }
}
