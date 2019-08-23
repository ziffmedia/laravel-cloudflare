<?php

namespace Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use ZiffDavis\Laravel\Cloudflare\Providers\CloudflareServiceProvider;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [CloudflareServiceProvider::class];
    }
}
