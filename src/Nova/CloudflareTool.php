<?php

namespace ZiffMedia\LaravelCloudflare\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Menu\MenuSection;
use Laravel\Nova\Tool;

class CloudflareTool extends Tool
{
    /**
     * Perform any tasks that need to happen when the tool is booted.
     *
     * @return void
     */
    public function boot()
    {
        // Nova::script('laravel-cloudflare-tool', __DIR__ . '/../../../dist/js/tool.js');
        // Nova::style('laravel-cloudflare-tool', __DIR__ . '/../../../dist/css/styles.css');
    }

    public function menu(Request $request)
    {
        return MenuSection::make('Cloudflare')
            ->path('cloudflare')
            ->icon('cloud');
    }

    // /**
    //  * Build the view that renders the navigation links for the tool.
    //  *
    //  * @return \Illuminate\View\View
    //  */
    // public function renderNavigation()
    // {
    //     return view('laravel-cloudflare::navigation');
    // }
}
