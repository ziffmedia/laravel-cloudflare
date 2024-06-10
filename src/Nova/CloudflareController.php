<?php

namespace ZiffMedia\LaravelCloudflare\Nova;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Laravel\Nova\Http\Requests\NovaRequest;

class CloudflareController extends Controller
{
    public function purgeCacheTags(NovaRequest $request): JsonResponse
    {
        $tags = $request->input('tags', []);

        cloudflare()->purgeCacheByCacheTags($tags);

        return response()->json(['purgedTags' => $tags]);
    }

    public function purgeUrls(NovaRequest $request): JsonResponse
    {
        $urls = $request->input('urls', []);

        cloudflare()->purgeCacheByUrls($urls);

        return response()->json(['purgedUrls' => $urls]);
    }
}
