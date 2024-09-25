<?php

return [

    /*
     * Allowed domains for cache clearing tool (in the Nova purge tool.) This
     * is an allow list to prevent sending purge requests for irrelevant domains
     * to the Cloudflare API.
     */
    'domains' => [
        //'example.com',
    ],

    /*
     * Cloudflare service configuration, defaults to pull from env or to null values.
     * These values can be pulled from the Cloudflare dashboard for your zone
     */
    'email' => env('CLOUDFLARE_EMAIL', null),
    'key'   => env('CLOUDFLARE_KEY', null),
    'token'   => env('CLOUDFLARE_TOKEN', null),
    'zone'  => env('CLOUDFLARE_ZONE', null),

    /*
     * Enable features in the base Cloudflare integration.
     *
     * purge_enabled: when enabled, allows all automated and manual purges to send requests to Cloudflare API
     */
    'purge_enabled'   => env('CLOUDFLARE_PURGE_ENABLED', false),

];
