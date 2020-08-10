<?php

return [
    'headers_enabled' => env('CLOUDFLARE_HEADERS_ENABLED', false),
    'purge_enabled'   => env('CLOUDFLARE_PURGE_ENABLED', false),
    'email'           => env('CLOUDFLARE_EMAIL', null),
    'key'             => env('CLOUDFLARE_KEY', null),
    'zone'            => env('CLOUDFLARE_ZONE', null),
];
