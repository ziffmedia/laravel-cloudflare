<?php

namespace ZiffMedia\LaravelCloudflare;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;
use ZiffMedia\LaravelCloudflare\Middleware\Authorize;

class LaravelCloudflareServiceProvider extends ServiceProvider
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

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-cloudflare');

        $this->app->booted(function () {
            $this->routes();
        });
        $this->app->singleton(Cloudflare::class, function () {
            return new Cloudflare(config('cloudflare.zone'), config('cloudflare.email'), config('cloudflare.key'));
        });

        Nova::serving(function (ServingNova $event) {
            Nova::script('laravel-cloudflare-field', __DIR__ . '/../dist/js/field.js');
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

        Route::middleware(['nova', Authorize::class])
            ->prefix('nova-vendor/laravel-cloudflare')
            ->namespace(__NAMESPACE__ . '\\Controllers')
            ->group(function ($route) {
                $route->post('/purge', 'CloudflareController@clearCacheWithUrls');
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
