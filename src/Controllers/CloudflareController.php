<?php

namespace ZiffMedia\LaravelCloudflare\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Laravel\Nova\Http\Requests\NovaRequest;
use ZiffMedia\LaravelCloudflare\Cloudflare;

class CloudflareController extends Controller
{
    public function clearCache(NovaRequest $request)
    {
        $urls = $request->input('urls', []);
        $tags = $request->input('tags', []);
        $validUrls = $this->validUrls($urls);
        $errorMessage = null;

        if ($validUrls) {
            try {
                app(Cloudflare::class)->purgeCacheByUrls($validUrls);
            } catch (\Exception $e) {
                $errorMessage = $e->getMessage();
            }
        }

        if ($tags) {
            try {
                app(Cloudflare::class)->setTags($tags)->purgeCacheByTags();
            } catch (\Exception $e) {
                $errorMessage = $e->getMessage();
            }
        }

        return ['purgedUrls' => $validUrls, 'purgedTags' => $tags, 'errorMessage' => $errorMessage];
    }

    private function validUrls(array $urls)
    {
        $domains = config('cloudflare.domains');

        if ($domains) {
            return array_values(array_filter($urls, function ($url) use ($domains) {
                return Str::contains($url, $domains);
            }));
        }

        return $urls;
    }
}
