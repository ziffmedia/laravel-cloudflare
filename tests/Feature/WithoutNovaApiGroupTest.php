<?php

namespace Tests\Feature;

use Tests\Fixtures\RemoveNovaApiGroup;
use Tests\TestCase;
use ZiffMedia\LaravelCloudflare\Nova\Tools\LaravelCloudflareTool;

/**
 * Runs like an app on Nova 3, which has no nova:api middleware group.
 */
class WithoutNovaApiGroupTest extends TestCase
{
    public const PURGE_URI = '/nova-vendor/laravel-cloudflare/purge';

    public const PURGE = ['urls' => ['https://example.com/'], 'tags' => ['articles-1']];

    protected function getPackageProviders($app): array
    {
        return [...parent::getPackageProviders($app), RemoveNovaApiGroup::class];
    }

    public function test_the_nova_api_group_is_gone(): void
    {
        $this->assertFalse($this->app['router']->hasMiddlewareGroup('nova:api'));
    }

    public function test_guests_are_rejected(): void
    {
        $this->postJson(self::PURGE_URI, self::PURGE)->assertUnauthorized();
    }

    public function test_users_who_fail_the_view_nova_gate_are_forbidden(): void
    {
        $this->actingAs($this->novaUser(viewNova: false))->postJson(self::PURGE_URI, self::PURGE)->assertForbidden();
    }

    public function test_the_login_and_view_nova_checks_run_before_the_tool_lookup(): void
    {
        $this->tools = [];

        $this->postJson(self::PURGE_URI, self::PURGE)->assertUnauthorized();
        $this->actingAs($this->novaUser(viewNova: false))->postJson(self::PURGE_URI, self::PURGE)->assertForbidden();
    }

    public function test_the_tool_can_see_check_decides_who_gets_in(): void
    {
        $this->tools = [LaravelCloudflareTool::make()->canSee(fn ($request) => $request->user()->getAuthIdentifier() === 2)];

        $this->actingAs($this->novaUser())->postJson(self::PURGE_URI, self::PURGE)->assertForbidden();
        $this->actingAs($this->novaUser()->forceFill(['id' => 2]))->postJson(self::PURGE_URI)->assertOk();
    }
}
