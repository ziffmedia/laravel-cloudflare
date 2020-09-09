<?php

namespace ZiffMedia\LaravelCloudflare;

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

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-cloudflare');

        $this->app->booted(function () {
            $this->routes();
        });
        $this->app->singleton(Cloudflare::class, function () {
            return new Cloudflare(config('cloudflare.zone'), config('cloudflare.email'), config('cloudflare.key'));
        });

        Nova::serving(function (ServingNova $event) {
            Nova::script('laravel-cloudflare-field', __DIR__ . '/../dist/js/field.js');
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

        Route::middleware(['nova'])
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
