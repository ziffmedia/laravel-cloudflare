<?php

namespace ZiffMedia\LaravelCloudflare;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Http\Middleware\Authenticate;
use Laravel\Nova\Http\Middleware\Authorize;
use Laravel\Nova\Nova;

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
            return new Cloudflare(config('cloudflare.zone'), config('cloudflare.email'), config('cloudflare.key'));
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

        Nova::router(['nova', Authenticate::class, Authorize::class], 'laravel-cloudflare')
            ->group(__DIR__.'/../routes/inertia.php');

        Route::middleware(['nova', Authorize::class])
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
