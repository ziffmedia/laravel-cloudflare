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
    $success = false;
    $message = "";
    try {
        app(Cloudflare::class)
        ->purgeCacheByUrl($urls);
        $message = 'CACHE CLEARED FOR: '. implode(", ", $urls) . ' !';
        $success = true;
    } catch (\Exception $e) {
        $message = $e->getMessage();
        $success = false;

    }
    return [$message, $success];
});
