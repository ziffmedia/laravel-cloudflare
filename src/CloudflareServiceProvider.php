<?php

namespace ZiffMedia\LaravelCloudflare;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Http\Middleware\Authenticate;
use Laravel\Nova\Http\Middleware\Authorize as NovaAuthorize;
use Laravel\Nova\Nova;
use ZiffMedia\LaravelCloudflare\Middleware\Authorize;

class CloudflareServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/cloudflare.php' => config_path('cloudflare.php'),
        ], 'config');

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'laravel-cloudflare');

        $this->app->booted(function () {
            $this->routes();
        });
        $this->app->singleton(Cloudflare::class, function () {
            return new Cloudflare(config('cloudflare.zone'), config('cloudflare.email'), config('cloudflare.key'), config('cloudflare.token'));
        });

        Nova::serving(function (ServingNova $event) {
            Nova::script('laravel-cloudflare-field', __DIR__ . '/../dist/js/field.js');
            Nova::style('laravel-cloudflare-field', __DIR__ . '/../dist/css/styles.css');
        });
    }

    /**
     * Register the tool's routes.
     *
     * @return void
     */
    protected function routes()
    {
        if ($this->app->routesAreCached()) {
            return;
        }

        $domain = config('cloudflare.domain') ?: config('nova.domain');

        // Nova 4 and later keep the login and viewNova checks in the nova:api group. Nova 3 has no such group,
        // so it gets the same checks by name.
        $middleware = Route::hasMiddlewareGroup('nova:api')
            ? ['nova:api', Authorize::class]
            : ['nova', Authenticate::class, NovaAuthorize::class, Authorize::class];

        Route::domain($domain)
            ->middleware($middleware)
            ->prefix('nova-vendor/laravel-cloudflare')
            ->namespace(__NAMESPACE__ . '\\Controllers')
            ->group(function ($route) {
                $route->post('/purge', 'CloudflareController@clearCache');
            });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
