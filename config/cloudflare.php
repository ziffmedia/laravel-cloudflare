<?php

return [

    /*
     * Cloudflare auth configuration, this can be set to any of the following:
     * - email and key
     * - user_service_key
     * - token
     * @see https://developers.cloudflare.com/api/
     * @see https://developers.cloudflare.com/api/tokens/create
     */
    'auth' => [
        /*
         * Email + Key Auth
         */
        'email' => env('CLOUDFLARE_AUTH_EMAIL', null),
        'key' => env('CLOUDFLARE_AUTH_KEY', null),

        /*
         * User Service Key Auth
         */
        'user_service_key' => env('CLOUDFLARE_AUTH_USER_SERVICE_KEY', null),

        /*
         * Token Auth
         */
        'token' => env('CLOUDFLARE_AUTH_TOKEN', null),
    ],

    /*
     * Cloudflare zone ID, this is required for all requests to the Cloudflare API.
     * Can be found in your dashboard on the right rail.
     */
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
