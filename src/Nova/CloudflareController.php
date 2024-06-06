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
        // $urls = explode("\n", $urlString);

        $domains = config('cloudflare.domains');

        if ($domains) {
            $urls = array_values(array_filter($urls, function ($url) use ($domains) {
                return Str::contains($url, $domains);
            }));
        }

        cloudflare()->purgeCacheByUrls($urls);

        return response()->json(['purgedUrls' => $urls]);
    }
}
