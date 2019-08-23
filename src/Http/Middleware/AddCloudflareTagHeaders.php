<?php

namespace ZiffDavis\Laravel\Cloudflare\Http\Middleware;

use Closure;
use Illuminate\Http\Response;
use ZiffDavis\Laravel\Cloudflare\Services\Cloudflare;

class AddCloudflareTagHeaders
{
    const HEADER_NAME = 'Cache-Tag';

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (config('cloudflare.headers_enabled')) {
            /** @var Response $response */
            $response = $next($request);

            $cloudflareTags = collect(app(Cloudflare::class)->getTags());
            if (!$cloudflareTags->isEmpty()) {
                $response->headers->add([self::HEADER_NAME => $cloudflareTags->implode(',')]);
            }

            return $response;
        }

        return $next($request);
    }
}
