<?php

namespace Tests\Fixtures;

use Illuminate\Http\Request;
use Laravel\Nova\Tool;

/**
 * Stands in for the package's LaravelCloudflareTool, which PHP cannot load on Nova 4 because it has no menu() method.
 * TestCase gives this class the LaravelCloudflareTool name, so the package's Authorize middleware treats it as the Tool.
 */
class CloudflareTool extends Tool
{
    public function menu(Request $request)
    {
        //
    }
}
