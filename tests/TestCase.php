<?php

namespace Tests;

use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Http;
use Inertia\ServiceProvider as InertiaServiceProvider;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaCoreServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;
use ZiffMedia\LaravelCloudflare\CloudflareServiceProvider;
use ZiffMedia\LaravelCloudflare\Nova\Tools\LaravelCloudflareTool;

abstract class TestCase extends BaseTestCase
{
    /**
     * The tools the app registers with Nova, set per test before the request.
     */
    protected array $tools = [];

    protected function setUp(): void
    {
        parent::setUp();

        // Nova keeps registered tools and the viewNova callback in static properties that outlive a test.
        Nova::$tools = [];
        Nova::$authUsing = null;

        // Sets the viewNova gate and registers tools when Nova starts serving, like an app's NovaServiceProvider.
        // Nova also registers its own Dashboard and ResourceManager tools on every request, so the list of
        // registered tools is never empty.
        $this->tools = [LaravelCloudflareTool::make()];
        Nova::serving(function () {
            Nova::auth(fn ($request) => (bool) $request->user()?->view_nova);
            Nova::tools($this->tools);
        });

        // Rendering a Nova page for a signed-in user may call Nova's license check.
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
