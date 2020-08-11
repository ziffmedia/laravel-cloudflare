<?php

use Illuminate\Http\Request;
use Laravel\Nova\Http\Requests\NovaRequest;
use Illuminate\Support\Facades\Route;
use ZiffMedia\LaravelCloudflare\Cloudflare;
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
        $domains = Config('cloudflare.domains');
        if ($domains) {
            foreach ($urls as $url) {
                if (str_contains($url, $domains)) {
                    array_push($valid_urls, $url);
                } else {
                    array_push($nonvalid_urls, $url);
                }
            }
        }
        app(Cloudflare::class)
        ->purgeCacheByUrl($valid_urls);
        if ($valid_urls.length > 0) {
            $successMessage = 'CACHE CLEARED FOR: '. implode(", ", $valid_urls) . ' !';
        }
        if($nonvalid_urls) {
            $errorMessage = 'DOMAINS NOT VALID: '. implode(", ", $nonvalid_urls) . ' !';
        }
    } catch (\Exception $e) {
        $errorMessage = $e->getMessage();

    }
    return [$errorMessage, $successMessage];
});
