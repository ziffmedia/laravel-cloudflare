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
    'key' => env('CLOUDFLARE_KEY', null),
    'zone' => env('CLOUDFLARE_ZONE', null),
];
