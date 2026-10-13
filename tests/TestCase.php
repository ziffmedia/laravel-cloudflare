<?php

namespace Tests;

use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Http;
use Inertia\ServiceProvider as InertiaServiceProvider;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaCoreServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;
use ZiffMedia\LaravelCloudflare\CloudflareServiceProvider;
use ZiffMedia\LaravelCloudflare\Nova\CloudflareTool;

abstract class TestCase extends BaseTestCase
{
    /**
     * The tools the app registers with Nova, set per test before the request.
     */
    protected array $tools = [];

    protected function setUp(): void
    {
        parent::setUp();

        // Nova keeps tools and other registrations in static arrays that outlive a test.
        Nova::flushState();

        // Stands in for an app's viewNova gate.
        Nova::auth(fn ($request) => (bool) $request->user()?->view_nova);

        // Registers tools when Nova starts serving, like an app's NovaServiceProvider::tools(). Nova adds its own
        // Dashboard and ResourceManager tools ahead of these on every request, so other tools always come first.
        $this->tools = [CloudflareTool::make()];
        Nova::serving(fn () => Nova::tools($this->tools));

        // Rendering a Nova page for a signed-in user calls Nova's license check.
        Http::preventStrayRequests();

        $this->withoutMix();
    }

    protected function getPackageProviders($app): array
    {
        // Testbench turns package discovery off, so list the providers the tests need.
        return [
            InertiaServiceProvider::class,
            NovaCoreServiceProvider::class,
            CloudflareServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('app.key', 'base64:'.base64_encode(str_repeat('a', 32)));
        $app['config']->set('cloudflare.purge_enabled', false);
    }

    protected function novaUser(bool $viewNova = true): User
    {
        return (new User)->forceFill(['id' => 1, 'view_nova' => $viewNova]);
    }
}
