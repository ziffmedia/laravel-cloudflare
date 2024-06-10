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
            'email' => $authEmail,
            'key' => $authKey,
            'user_service_key' => $authUserServiceKey,
            'token' => $authToken,
        ] = config('cloudflare.auth');

        $authHeaders = match(true) {
            $authEmail && $authKey => ['X-Auth-Email' => $authEmail, 'X-Auth-Key' => $authKey],
            $authUserServiceKey && true => ['X-Auth-User-Service-Key' => $authUserServiceKey],
            $authToken && true => ['Authorization' => 'Bearer '.$authToken],
            default => null
        };

        $zone = config('cloudflare.zone');

        $this->app->singleton(Cloudflare::class, fn () => new Cloudflare());

        // check to see if the API is callable
        if ($authHeaders && $zone) {
            Http::macro(
                'cloudflare',
                fn () => Http::withHeaders($authHeaders)
                    ->baseUrl('https://api.cloudflare.com/client/v4/zones/'.($zone ?? 'no-zone-provided'))
            );
        } else {
            // fake the API calls
            Http::macro(
                'cloudflare',
                fn () => Http::fake(function ($request) {
                    logger()->info('Fake Cloudflare API Request (missing email, key, zone)', ['url' => $request->url(), 'data' => $request->data()]);

                    return Http::response('Fake Cloudflare API Request');
                })
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
