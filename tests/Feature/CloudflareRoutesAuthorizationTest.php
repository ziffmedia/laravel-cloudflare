<?php

namespace Tests\Feature;

use Laravel\Nova\Http\Middleware\Authenticate;
use Laravel\Nova\Http\Middleware\Authorize as NovaAuthorize;
use Orchestra\Testbench\Attributes\DefineRoute;
use Orchestra\Testbench\Attributes\WithConfig;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\Fixtures\RejectRequest;
use Tests\TestCase;
use ZiffMedia\LaravelCloudflare\Cloudflare;
use ZiffMedia\LaravelCloudflare\Nova\CloudflareTool;

class CloudflareRoutesAuthorizationTest extends TestCase
{
    public static function purgeRoutes(): array
    {
        return [
            'purge urls' => ['postJson', '/nova-vendor/cloudflare/purge-urls'],
            'purge cache tags' => ['postJson', '/nova-vendor/cloudflare/purge-cache-tags'],
        ];
    }

    public static function routes(): array
    {
        return static::purgeRoutes() + [
            'tool page' => ['getJson', '/nova/cloudflare'],
        ];
    }

    #[DataProvider('routes')]
    public function test_guests_are_rejected(string $method, string $uri): void
    {
        $this->{$method}($uri)->assertUnauthorized();
    }

    #[DefineRoute('defineLoginRoute')]
    public function test_guests_without_a_json_request_are_rejected(): void
    {
        $cloudflare = $this->spy(Cloudflare::class);

        $this->post('/nova-vendor/cloudflare/purge-urls', ['urls' => ['https://example.com/']])->assertUnauthorized();
        $this->post('/nova-vendor/cloudflare/purge-cache-tags', ['tags' => ['articles-1']])->assertUnauthorized();
        $this->get('/nova/cloudflare')->assertRedirect('/nova/login');

        $cloudflare->shouldNotHaveReceived('purgeCacheByUrls');
        $cloudflare->shouldNotHaveReceived('purgeCacheByCacheTags');
    }

    #[DataProvider('routes')]
    public function test_users_who_fail_the_view_nova_gate_are_forbidden(string $method, string $uri): void
    {
        $this->actingAs($this->novaUser(viewNova: false))->{$method}($uri)->assertForbidden();
    }

    #[DataProvider('routes')]
    #[WithConfig('nova.api_middleware', ['nova', Authenticate::class, NovaAuthorize::class, RejectRequest::class], defer: false)]
    public function test_routes_use_the_apps_nova_api_middleware(string $method, string $uri): void
    {
        $this->actingAs($this->novaUser())->{$method}($uri)->assertStatus(418);
    }

    #[DataProvider('routes')]
    #[WithConfig('cloudflare.routing.middleware', ['nova:api', RejectRequest::class], defer: false)]
    public function test_routes_use_the_routing_middleware(string $method, string $uri): void
    {
        $this->actingAs($this->novaUser())->{$method}($uri)->assertStatus(418);
    }

    #[DataProvider('routes')]
    #[WithConfig('cloudflare.routing.middleware', [], defer: false)]
    public function test_an_empty_routing_middleware_uses_nova_api(string $method, string $uri): void
    {
        $this->{$method}($uri)->assertUnauthorized();
    }

    #[DataProvider('routes')]
    public function test_the_tool_can_see_check_decides_who_gets_in(string $method, string $uri): void
    {
        $this->tools = [CloudflareTool::make()->canSee(fn ($request) => $request->user()->getAuthIdentifier() === 2)];
        $cloudflare = $this->spy(Cloudflare::class);

        $this->actingAs($this->novaUser())->{$method}($uri)->assertForbidden();
        $cloudflare->shouldNotHaveReceived('purgeCacheByUrls');
        $cloudflare->shouldNotHaveReceived('purgeCacheByCacheTags');

        $this->actingAs($this->novaUser()->forceFill(['id' => 2]))->{$method}($uri)->assertOk();
    }

    #[DataProvider('routes')]
    public function test_routes_are_not_found_when_the_app_does_not_register_the_tool(string $method, string $uri): void
    {
        $this->tools = [];
        $cloudflare = $this->spy(Cloudflare::class);

        $this->actingAs($this->novaUser())->{$method}($uri)->assertNotFound();
        $cloudflare->shouldNotHaveReceived('purgeCacheByUrls');
        $cloudflare->shouldNotHaveReceived('purgeCacheByCacheTags');
    }

    #[DataProvider('routes')]
    #[WithConfig('nova.domain', 'cms.test', defer: false)]
    public function test_routes_only_answer_on_the_nova_domain(string $method, string $uri): void
    {
        $this->actingAs($this->novaUser());

        $this->{$method}('http://localhost'.$uri)->assertNotFound();
        $this->{$method}('http://cms.test'.$uri)->assertOk();
    }

    #[DataProvider('routes')]
    #[WithConfig('nova.domain', 'cms.test', defer: false)]
    #[WithConfig('cloudflare.routing.domain', '', defer: false)]
    public function test_an_empty_routing_domain_uses_the_nova_domain(string $method, string $uri): void
    {
        $this->actingAs($this->novaUser());

        $this->{$method}('http://localhost'.$uri)->assertNotFound();
        $this->{$method}('http://cms.test'.$uri)->assertOk();
    }

    #[DataProvider('routes')]
    #[WithConfig('cloudflare.routing.domain', 'cms.test', defer: false)]
    public function test_routes_only_answer_on_the_routing_domain(string $method, string $uri): void
    {
        $this->actingAs($this->novaUser());

        $this->{$method}('http://localhost'.$uri)->assertNotFound();
        $this->{$method}('http://cms.test'.$uri)->assertOk();
    }

    /**
     * Laravel 11's default app setup sends guests to route('login'), so the test app needs a route by that name.
     */
    protected function defineLoginRoute($router): void
    {
        $router->get('login', fn () => 'login')->name('login');
    }

    public function test_allowed_users_can_purge_and_open_the_tool(): void
    {
        $cloudflare = $this->spy(Cloudflare::class);
        $this->actingAs($this->novaUser());

        $this->postJson('/nova-vendor/cloudflare/purge-urls', ['urls' => ['https://example.com/']])
            ->assertOk()
            ->assertExactJson(['purgedUrls' => ['https://example.com/']]);

        $this->postJson('/nova-vendor/cloudflare/purge-cache-tags', ['tags' => ['articles-1']])
            ->assertOk()
            ->assertExactJson(['purgedTags' => ['articles-1']]);

        $cloudflare->shouldHaveReceived('purgeCacheByUrls')->once()->with(['https://example.com/']);
        $cloudflare->shouldHaveReceived('purgeCacheByCacheTags')->once()->with(['articles-1']);

        $this->get('/nova/cloudflare')->assertOk();
    }
}
