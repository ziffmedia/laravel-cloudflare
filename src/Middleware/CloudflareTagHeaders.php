<?php

namespace ZiffMedia\LaravelCloudflare\Middleware;

use Closure;
use ZiffMedia\LaravelCloudflare\Cloudflare;

class CloudflareTagHeaders
{
    const HEADER_NAME = 'Cache-Tag';

    const TAG_TEST_HEADER = 'debug_cache_tags';

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $testCacheTags = $request->query->has(self::TAG_TEST_HEADER);
        $response = $next($request);
        $cloudflareTags = collect(app(Cloudflare::class)->getTags());

        if (! $cloudflareTags->isEmpty()) {
            $response->headers->add([self::HEADER_NAME => $cloudflareTags->implode(',')]);
            if ($testCacheTags) {
                $response->headers->add([self::TAG_TEST_HEADER => $cloudflareTags->implode(',')]);
            }
        }

        return $response;
    }
}
