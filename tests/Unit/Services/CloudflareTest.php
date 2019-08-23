<?php

namespace Tests\Unit;

use ZiffDavis\Laravel\Cloudflare\Services\Cloudflare;
use Cloudflare\API\Endpoints\Zones;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class CloudflareTest extends TestCase
{
    protected $cloudflare;

    public function setUp() : void
    {
        parent::setUp();

        Config::set('cloudflare.purge_enabled', true);
        $this->cloudflare = new Cloudflare('testzone', 'test@test.com', '123456');
    }

    public function testPurgeCacheByTagsWhenPurgeIsEnabledInvokesCloudflareZoneCachePurge()
    {
        $tagsToPurge = ['test_1234', 'reviews_4432'];
        $this->cloudflare->setTags($tagsToPurge);

        $mockZonesEndpoint = $this->getMockBuilder(Zones::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockZonesEndpoint->expects($this->once())
            ->method('cachePurge');
        $this->cloudflare->setCloudflareZonesEndpoint($mockZonesEndpoint);

        $this->cloudflare->purgeCacheByTags();
    }

    public function testPurgeCacheByTagsWhenPurgeResultsInExceptionRethrowsException()
    {
        $this->cloudflare->setTags(['test_1234']);

        $mockZonesEndpoint = $this->getMockBuilder(Zones::class)
            ->disableOriginalConstructor()
            ->getMock();
        $mockZonesEndpoint->method('cachePurge')
            ->willThrowException(new \Exception('error'));
        $this->cloudflare->setCloudflareZonesEndpoint($mockZonesEndpoint);

        $this->expectException(\Exception::class);
        $this->cloudflare->purgeCacheByTags();
    }

    public function testPurgeCacheByTagsEmptiesStoredTags()
    {
        $this->cloudflare->setTags(['test_1234']);

        $mockZonesEndpoint = $this->getMockBuilder(Zones::class)
            ->disableOriginalConstructor()
            ->setMethods(['cachePurge'])
            ->getMock();
        $this->cloudflare->setCloudflareZonesEndpoint($mockZonesEndpoint);

        $this->assertEquals(['test_1234'], $this->cloudflare->getTags());
        $this->cloudflare->purgeCacheByTags();
        $this->assertEquals([], $this->cloudflare->getTags());
    }
}
