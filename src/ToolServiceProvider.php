<?php

namespace ZiffMedia\LaravelCloudflare;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;
use ZiffMedia\LaravelCloudflare\Http\Middleware\Authorize;

class ToolServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-cloudflare');

        $this->app->booted(function () {
            $this->routes();
        });
        $this->app->singleton(Cloudflare::class, function () {
            return new Cloudflare("e543938826d94c4ef0b129d96ef9a172", "josh@ziffdavis.com", "9f94ff7ea4fe5b66e545df2f9efa439ee0060");
        });
        Nova::serving(function (ServingNova $event) {
            //
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
                ->group(__DIR__.'/../routes/api.php');
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
