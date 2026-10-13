<?php

namespace ZiffMedia\LaravelCloudflare\Middleware;

use Illuminate\Http\Request;
use Laravel\Nova\Nova;
use ZiffMedia\LaravelCloudflare\Nova\Tools\LaravelCloudflareTool;

class Authorize
{
    /**
     * Handle the incoming request.
     *
     * @param  Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, $next)
    {
        $tool = collect(Nova::registeredTools())->first([$this, 'matchesTool']);

        if (is_null($tool)) {
            abort(404);
        }

        if (! $tool->authorize($request)) {
            abort(403);
        }

        return $next($request);
    }

    /**
     * Determine whether this tool belongs to the package.
     *
     * @return bool
     */
    public function matchesTool($tool)
    {
        return $tool instanceof LaravelCloudflareTool;
    }
}
