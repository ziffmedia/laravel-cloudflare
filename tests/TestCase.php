<?php

namespace Tests;

use Illuminate\Foundation\Auth\User;
use Inertia\ServiceProvider as InertiaServiceProvider;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaCoreServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Tests\Fixtures\CloudflareTool;
use ZiffMedia\LaravelCloudflare\CloudflareServiceProvider;
use ZiffMedia\LaravelCloudflare\Nova\Tools\LaravelCloudflareTool;

abstract class TestCase extends BaseTestCase
{
    /**
     * The tools the app registers with Nova, set per test before the request.
     */
    protected array $tools = [];

    /**
     * Whether to stand in for LaravelCloudflareTool. Without it, the test runs like an app on Nova 4, where the real
     * Tool cannot load.
     */
    protected bool $usesToolStandIn = true;

    protected function setUp(): void
    {
        parent::setUp();

        // The real Tool cannot load on Nova 4, because it has no menu() method, so the stand-in takes its name
        // before anything tries to load it. Remove the stand-in once the real Tool has a menu() method.
        if ($this->usesToolStandIn && ! class_exists(LaravelCloudflareTool::class, false)) {
            class_alias(CloudflareTool::class, LaravelCloudflareTool::class);
        }

        // Nova keeps registered tools and the viewNova callback in static properties that outlive a test.
        Nova::$tools = [];
        Nova::$authUsing = null;

        // Sets the viewNova gate and registers tools when Nova starts serving, like an app's NovaServiceProvider.
        $this->tools = $this->usesToolStandIn ? [LaravelCloudflareTool::make()] : [];
        Nova::serving(function () {
            Nova::auth(fn ($request) => (bool) $request->user()?->view_nova);
            Nova::tools($this->tools);
        });
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
        $app['config']->set('app.key', 'base64:' . base64_encode(str_repeat('a', 32)));
        $app['config']->set('cloudflare.purge_enabled', false);
    }

    protected function novaUser(bool $viewNova = true): User
    {
        return (new User)->forceFill(['id' => 1, 'view_nova' => $viewNova]);
    }
}
