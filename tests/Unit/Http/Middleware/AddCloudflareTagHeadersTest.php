<?php

namespace Tests\Unit\Middleware;

use ZiffDavis\Laravel\Cloudflare\Http\Middleware\AddCloudflareTagHeaders;
use ZiffDavis\Laravel\Cloudflare\Services\Cloudflare;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class AddCloudflareTagHeadersTest extends TestCase
{
    public function testCacheTagHeaderIsAddedToResponse()
    {
        Config::set('cloudflare.headers_enabled', true);
        app(Cloudflare::class)->setTags(['test_1234', 'test_34567']);

        $response = (new AddCloudflareTagHeaders())
            ->handle(new Request(), function () {
                return new Response();
            });
        $this->assertEquals(['test_1234,test_34567'], $response->headers->all()['cache-tag']);
    }

    public function testCacheTagHeaderIsNotAddedWhenTagsIsEmpty()
    {
        Config::set('cloudflare.headers_enabled', true);
        $response = (new AddCloudflareTagHeaders())
            ->handle(new Request(), function () {
                return new Response();
            });
        $this->assertArrayNotHasKey('cache-tag', $response->headers->all());
    }
}
