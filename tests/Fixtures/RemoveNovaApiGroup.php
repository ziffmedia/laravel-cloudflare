<?php

namespace Tests\Fixtures;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use ReflectionProperty;

/**
 * Removes Nova's nova:api middleware group after Nova adds it, so the app looks like it runs Nova 3.
 */
class RemoveNovaApiGroup extends ServiceProvider
{
    public function boot(Router $router): void
    {
        $groups = new ReflectionProperty(Router::class, 'middlewareGroups');
        $groups->setValue($router, collect($groups->getValue($router))->except('nova:api')->all());
    }
}
