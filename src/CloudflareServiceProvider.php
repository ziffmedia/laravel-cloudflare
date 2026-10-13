<?php

namespace ZiffMedia\LaravelCloudflare;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
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
            __DIR__.'/../config/cloudflare.php' => config_path('cloudflare.php'),
        ], 'config');

        $this->app->booted(function () {
            $this->routes();
        });
        $this->app->singleton(Cloudflare::class, function () {
            return new Cloudflare(config('cloudflare.zone'), config('cloudflare.email'), config('cloudflare.key'), config('cloudflare.token'));
        });

        Nova::serving(function (ServingNova $event) {
            Nova::script('laravel-cloudflare-field', __DIR__.'/../dist/js/field.js');
            Nova::style('laravel-cloudflare-field', __DIR__.'/../dist/css/styles.css');
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

        // One domain and one middleware list for the Tool page and the purge route, so they always match.
        $domain = config('cloudflare.routing.domain') ?: config('nova.domain');
        $middleware = array_merge(Arr::wrap(config('cloudflare.routing.middleware')) ?: ['nova:api'], [Authorize::class]);

        Nova::router($middleware, 'laravel-cloudflare')
            ->domain($domain)
            ->group(__DIR__.'/../routes/inertia.php');

        Route::domain($domain)
            ->middleware($middleware)
            ->prefix('nova-vendor/laravel-cloudflare')
            ->namespace(__NAMESPACE__.'\\Controllers')
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
