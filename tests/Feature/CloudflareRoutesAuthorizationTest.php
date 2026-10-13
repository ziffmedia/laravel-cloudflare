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
use ZiffMedia\LaravelCloudflare\Nova\Tools\LaravelCloudflareTool;

class CloudflareRoutesAuthorizationTest extends TestCase
{
    public const PURGE_URI = '/nova-vendor/laravel-cloudflare/purge';

    public const PURGE = ['urls' => ['https://example.com/'], 'tags' => ['articles-1']];

    public static function routes(): array
    {
        return [
            'purge' => ['POST', self::PURGE_URI],
            'tool page' => ['GET', '/nova/laravel-cloudflare'],
        ];
    }

    #[DataProvider('routes')]
    public function test_guests_are_rejected(string $method, string $uri): void
    {
        $this->tools = [$this->toolForUserOne()];

        $this->json($method, $uri, self::PURGE)->assertUnauthorized();
    }

    #[DefineRoute('defineLoginRoute')]
    public function test_guests_without_a_json_request_are_rejected(): void
    {
        $this->tools = [$this->toolForUserOne()];
        $cloudflare = $this->spy(Cloudflare::class);

        $this->post(self::PURGE_URI, self::PURGE)->assertUnauthorized();
        $this->get('/nova/laravel-cloudflare')->assertRedirect('/nova/login');

        $cloudflare->shouldNotHaveReceived('purgeCacheByUrls');
        $cloudflare->shouldNotHaveReceived('setTags');
    }

    #[DataProvider('routes')]
    public function test_users_who_fail_the_view_nova_gate_are_forbidden(string $method, string $uri): void
    {
        $this->actingAs($this->novaUser(viewNova: false))->json($method, $uri, self::PURGE)->assertForbidden();
    }

    #[DataProvider('routes')]
    #[WithConfig('nova.api_middleware', ['nova', Authenticate::class, NovaAuthorize::class, RejectRequest::class], defer: false)]
    public function test_routes_use_the_apps_nova_api_middleware(string $method, string $uri): void
    {
        $this->actingAs($this->novaUser())->json($method, $uri, self::PURGE)->assertStatus(418);
    }

    #[DataProvider('routes')]
    #[WithConfig('cloudflare.routing.middleware', ['nova:api', RejectRequest::class], defer: false)]
    public function test_routes_use_the_routing_middleware(string $method, string $uri): void
    {
        $this->actingAs($this->novaUser())->json($method, $uri, self::PURGE)->assertStatus(418);
    }

    #[DataProvider('routes')]
    #[WithConfig('cloudflare.routing.middleware', [], defer: false)]
    public function test_an_empty_routing_middleware_uses_nova_api(string $method, string $uri): void
    {
        $this->json($method, $uri, self::PURGE)->assertUnauthorized();
    }

    #[DataProvider('routes')]
    public function test_the_tool_can_see_check_decides_who_gets_in(string $method, string $uri): void
    {
        $this->tools = [LaravelCloudflareTool::make()->canSee(fn ($request) => $request->user()->getAuthIdentifier() === 2)];
        $cloudflare = $this->spy(Cloudflare::class);
        $cloudflare->shouldReceive('setTags')->andReturnSelf();

        $this->actingAs($this->novaUser())->json($method, $uri, self::PURGE)->assertForbidden();
        $cloudflare->shouldNotHaveReceived('purgeCacheByUrls');
        $cloudflare->shouldNotHaveReceived('setTags');

        $this->actingAs($this->novaUser()->forceFill(['id' => 2]))->json($method, $uri, self::PURGE)->assertOk();
    }

    #[DataProvider('routes')]
    public function test_routes_are_not_found_when_the_app_does_not_register_the_tool(string $method, string $uri): void
    {
        $this->tools = [];
        $cloudflare = $this->spy(Cloudflare::class);

        $this->actingAs($this->novaUser())->json($method, $uri, self::PURGE)->assertNotFound();
        $cloudflare->shouldNotHaveReceived('purgeCacheByUrls');
        $cloudflare->shouldNotHaveReceived('setTags');
    }

    #[DataProvider('routes')]
    public function test_the_login_and_view_nova_checks_run_before_the_tool_lookup(string $method, string $uri): void
    {
        $this->tools = [];

        $this->json($method, $uri, self::PURGE)->assertUnauthorized();
        $this->actingAs($this->novaUser(viewNova: false))->json($method, $uri, self::PURGE)->assertForbidden();
    }

    #[WithConfig('cloudflare.routing.domain', 'cms.test', defer: false)]
    public function test_the_routes_only_answer_on_the_routing_domain(): void
    {
        $this->actingAs($this->novaUser());

        $this->postJson('http://localhost'.self::PURGE_URI)->assertNotFound();
        $this->postJson('http://cms.test'.self::PURGE_URI)->assertOk();
        $this->get('http://localhost/nova/laravel-cloudflare')->assertNotFound();
        $this->get('http://cms.test/nova/laravel-cloudflare')->assertOk();
    }

    #[WithConfig('nova.domain', 'cms.test', defer: false)]
    #[WithConfig('cloudflare.routing.domain', '', defer: false)]
    public function test_an_empty_routing_domain_uses_the_nova_domain(): void
    {
        $this->actingAs($this->novaUser());

        $this->postJson('http://localhost'.self::PURGE_URI)->assertNotFound();
        $this->postJson('http://cms.test'.self::PURGE_URI)->assertOk();
        $this->get('http://localhost/nova/laravel-cloudflare')->assertNotFound();
        $this->get('http://cms.test/nova/laravel-cloudflare')->assertOk();
    }

    #[WithConfig('nova.domain', 'cms.test', defer: false)]
    public function test_the_routes_only_answer_on_the_nova_domain(): void
    {
        $this->actingAs($this->novaUser());

        $this->postJson('http://localhost'.self::PURGE_URI)->assertNotFound();
        $this->postJson('http://cms.test'.self::PURGE_URI)->assertOk();
        $this->get('http://localhost/nova/laravel-cloudflare')->assertNotFound();
        $this->get('http://cms.test/nova/laravel-cloudflare')->assertOk();
    }

    /**
     * A canSee that reads the signed-in user, as apps usually write it. A guest who reached it would get a 500, not a 401.
     */
    protected function toolForUserOne(): LaravelCloudflareTool
    {
        return LaravelCloudflareTool::make()->canSee(fn ($request) => $request->user()->getAuthIdentifier() === 1);
    }

    /**
     * Laravel 11's default app setup sends guests to route('login'), so Testbench needs a route with that name.
     */
    protected function defineLoginRoute($router): void
    {
        $router->get('login', fn () => 'login')->name('login');
    }

    public function test_allowed_users_can_purge_and_open_the_tool(): void
    {
        $cloudflare = $this->spy(Cloudflare::class);
        $cloudflare->shouldReceive('setTags')->andReturnSelf();
        $this->actingAs($this->novaUser());

        $this->postJson(self::PURGE_URI, self::PURGE)
            ->assertOk()
            ->assertExactJson(['purgedUrls' => ['https://example.com/'], 'purgedTags' => ['articles-1'], 'errorMessage' => null]);

        $cloudflare->shouldHaveReceived('purgeCacheByUrls')->once()->with(['https://example.com/']);
        $cloudflare->shouldHaveReceived('setTags')->once()->with(['articles-1']);
        $cloudflare->shouldHaveReceived('purgeCacheByTags')->once();

        $this->get('/nova/laravel-cloudflare')->assertOk();
    }
}
