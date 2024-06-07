<?php

namespace ZiffMedia\LaravelCloudflare;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Laravel\Nova\Nova;
use ZiffMedia\LaravelCloudflare\Nova\CloudflareController;

class CloudflareServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/cloudflare.php', 'cloudflare'
        );
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/cloudflare.php' => config_path('cloudflare.php'),
        ], 'config');

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-cloudflare');

        [
            'zone' => $zone,
            'email' => $email,
            'key' => $key,
        ] = config('cloudflare');

        $this->app->singleton(Cloudflare::class, fn () => new Cloudflare());

        // check to see if the API is callable
        if (! ($email && $key && $zone)) {
            // fake the API calls
            Http::macro(
                'cloudflare',
                fn () => Http::fake(function ($request) {
                    logger()->info('Fake Cloudflare API Request (missing email, key, zone)', ['url' => $request->url(), 'data' => $request->data()]);

                    return Http::response('Fake Cloudflare API Request');
                })
            );
        } else {
            // real the API calls
            Http::macro(
                'cloudflare',
                fn () => Http::withHeaders(['X-Auth-Email' => $email, 'X-Auth-Key' => $key])
                    ->baseUrl('https://api.cloudflare.com/client/v4/zones/'.($zone ?? 'no-zone-provided'))
            );
        }

        Nova::router(['nova'], 'cloudflare')
            ->group(fn ($router) => $router->get('/', fn () => inertia('CloudflareTool')));

        Route::middleware(['nova'])
            ->prefix('nova-vendor/cloudflare')
            ->namespace(__NAMESPACE__.'\\Controllers')
            ->group(function ($router) {
                $router->post('/purge-cache-tags', [CloudflareController::class, 'purgeCacheTags']);
                $router->post('/purge-urls', [CloudflareController::class, 'purgeUrls']);
            });

        Nova::serving(function () {
            Nova::script('cloudflare', __DIR__.'/../dist/js/cloudflare.js');
        });
    }
}
