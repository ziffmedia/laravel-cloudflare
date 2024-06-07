<?php

return [

    /*
     * Allowed domains for cache clearing tool (in the Nova purge tool.) This
     * is an allow list to prevent sending purge requests for irrelevant domains
     * to the Cloudflare API.
     */
    'domains' => [
        // 'example.com',
    ],

    /*
     * Cloudflare service configuration, defaults to pull from env or to null values.
     * These values can be pulled from the Cloudflare dashboard for your zone
     */
    'email' => env('CLOUDFLARE_EMAIL', null),
    'key' => env('CLOUDFLARE_KEY', null),
    'zone' => env('CLOUDFLARE_ZONE', null),

    /*
     * Enable features in the base Cloudflare integration.
     *
     * purge_enabled: when enabled, allows all automated and manual purges to send requests to Cloudflare API
     */
    'purge_enabled' => env('CLOUDFLARE_PURGE_ENABLED', false),

    /*
     * Allow header debugging: when enabled, the Cloudflare API response headers will be
     * included in the response from the Cloudflare API. This can be useful for debugging
     * API responses.
     */
    'allow_header_debugging' => env('CLOUDFLARE_ALLOW_HEADER_DEBUGGING', false),
];
