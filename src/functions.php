<?php

use ZiffMedia\LaravelCloudflare\Cloudflare;

if (! function_exists('cloudflare')) {
    function cloudflare(): Cloudflare {
        $cloudflare = app(Cloudflare::class);

        if (func_num_args() === 0) {
            return $cloudflare;
        }

        return $cloudflare->addResponseCacheTag(...func_get_args());
    }
}
