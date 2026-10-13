<?php

namespace ZiffMedia\LaravelCloudflare\Nova;

use Closure;
use Illuminate\Http\Request;
use Laravel\Nova\Nova;
use Laravel\Nova\Tool;
use Symfony\Component\HttpFoundation\Response;

class Authorize
{
    /**
     * Handle the incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
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
     */
    public function matchesTool(Tool $tool): bool
    {
        return $tool instanceof CloudflareTool;
    }
}
