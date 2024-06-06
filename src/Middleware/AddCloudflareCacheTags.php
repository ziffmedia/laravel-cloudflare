<?php

namespace ZiffMedia\LaravelCloudflare\Middleware;

use Closure;
use Illuminate\Support\Arr;

class AddCloudflareCacheTags
{
    public function handle($request, Closure $next): mixed
    {
        $response = $next($request);

        $cacheTags = cloudflare()->getResponseCacheTags();

        if ($cacheTags) {
            $cacheTagsString = Arr::join($cacheTags, ',');

            $response->header('Cache-Tag', $cacheTagsString);

            if ((config('cloudflare.allow_header_debugging') || config('app.debug')) && $request->query->has('debug-cloudflare-cache-tags')) {
                $response->header('X-Cache-Tags-Info', $cacheTagsString);
            }
        }

        return $response;
    }
}
