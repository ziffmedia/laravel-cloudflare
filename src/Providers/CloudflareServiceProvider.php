<?php
namespace ZiffDavis\Laravel\Cloudflare\Providers;

use Illuminate\Support\ServiceProvider;
use ZiffDavis\Laravel\Cloudflare\Services\Cloudflare;

class CloudflareServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../../config/cloudflare.php', 'cloudflare');

        $this->app->singleton(Cloudflare::class, function () {
            return new Cloudflare(config('cloudflare.zone'), config('cloudflare.email'), config('cloudflare.key'));
        });
    }
}
