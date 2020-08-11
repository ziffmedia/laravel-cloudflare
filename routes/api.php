<?php

use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Support\Facades\Route;
use ZiffMedia\LaravelCloudflare\Cloudflare;
use Illuminate\Support\Str;
/*
|--------------------------------------------------------------------------
| Tool API Routes
|--------------------------------------------------------------------------
|
| Here is where you may register API routes for your tool. These routes
| are loaded by the ServiceProvider of your tool. They are protected
| by your tool's "Authorize" middleware by default. Now, go build!
|
*/

// Route::get('/endpoint', function (Request $request) {
//     //
// });
Route::post('/purge', function (NovaRequest $request) {
    $urls = $request->input('urls');
    $errorMessage = null;
    $successMessage =null;

    try {
        $valid_urls = [];
        $nonvalid_urls = [];
        $domains = config('cloudflare.domains');
        if ($domains) {
            foreach ($urls as $url) {
                if (Str::contains($url, $domains)) {
                    array_push($valid_urls, $url);
                } else {
                    array_push($nonvalid_urls, $url);
                }
            }
        } else {
            $valid_urls = $urls;
        }
        app(Cloudflare::class)
        ->purgeCacheByUrl($valid_urls);
        if (count($valid_urls) > 0) {
            $successMessage = 'CACHE CLEARED FOR: '. implode(", ", $valid_urls) . ' !';
        }
        if(count($nonvalid_urls) > 0){
            $errorMessage = 'DOMAINS NOT VALID: '. implode(", ", $nonvalid_urls) . ' !';
        }
    } catch (\Exception $e) {
        $errorMessage = $e->getMessage();

    }
    return [$errorMessage, $successMessage];
});
