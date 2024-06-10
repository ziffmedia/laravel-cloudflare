<?php

namespace ZiffMedia\LaravelCloudflare\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Menu\MenuSection;
use Laravel\Nova\Tool;

class CloudflareTool extends Tool
{
    public function menu(Request $request)
    {
        return MenuSection::make('Cloudflare')
            ->path('cloudflare')
            ->icon('cloud');
    }
}
