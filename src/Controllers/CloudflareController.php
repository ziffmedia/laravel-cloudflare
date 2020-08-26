<?php

namespace ZiffMedia\LaravelCloudflare\Controllers;


use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Laravel\Nova\Http\Requests\NovaRequest;
use ZiffMedia\LaravelCloudflare\Cloudflare;

class CloudflareController extends Controller
{
    public function clearCacheWithUrls(NovaRequest $request)
    {
        $urls = $request->input('urls', []);
        $validUrls = $this->validUrls($urls);
        $nonValidUrls = array_diff($urls, $validUrls);
        $errorMessage = null;

        if ($validUrls) {
            try {
                $cloudflare = new Cloudflare();
                $cloudflare->purgeCacheByUrl($validUrls);
            } catch (\Exception $e) {
                $errorMessage = $e->getMessage();
            }
        }

        return ['validUrls' => $validUrls, 'invalidUrls' => $nonValidUrls, 'errorMessage' => $errorMessage];
    }

    private function validUrls(Array $urls) {
        $domains = config('cloudflare.domains');

        if ($domains) {
            return array_values(array_filter( $urls, function( $url ) use ( $domains ) {
                return Str::contains($url, $domains);
            }));
        }

        return $urls;
    }
}

