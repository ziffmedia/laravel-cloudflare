<?php

namespace ZiffMedia\LaravelCloudflare;

use Cloudflare\API\Adapter\Guzzle;
use Cloudflare\API\Auth\APIKey;
use Cloudflare\API\Endpoints\Zones;
use Illuminate\Support\Collection;

class Cloudflare
{
    /** @var array tags */
    protected $tags = [];

    protected $email;
    protected $key;
    protected $zone;

    /** @var Zones */
    protected $zonesEndpoint;

    public function __construct($zone, $email, $key)
    {
        $this->zone = $zone;
        $this->email = $email;
        $this->key = $key;

    }

    public function addTag(string $tag)
    {
        $this->tags[] = $tag;
    }

    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param array $tags
     */
    public function setTags(array $tags)
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * @throws \Exception
     */
    public function purgeCacheByTags()
    {
        if (config('cloudflare.purge_enabled')) {
            $zone = $this->getCloudflareZonesEndpoint();

            try {
                collect($this->tags)->chunk(25)->each(function (Collection $chunk) use ($zone) {
                    $zone->cachePurge($this->zone, null, array_values($chunk->toArray()));
                });
                $this->tags = [];
            } catch (\Exception $e) {
                logger()->warning('Cloudflare::purgeCacheByTags failed, tags=' . implode(',', $this->tags) . ' - code=' . $e->getCode() . ' - message=' . str_replace("\n", '', (string) $e->getMessage()));

                throw $e;
            }
        } else {
            logger()->info('Cloudflare::purgeCacheByTags requested but purge is not enabled - tags=' . implode(',', $this->tags));
        }
    }

    public function purgeCacheByUrls($urls)
    {
        if (Config('cloudflare.purge_enabled')) {
            $zone = $this->getCloudflareZonesEndpoint();
            try {
                $zone->cachePurge($this->zone, $urls);
            } catch (\Exception $e) {
                logger()->warning($e->getMessage());

                throw $e;
            }
        } else {
            logger()->info('Cloudflare::purgeCacheByUrl requested but purge is not enabled');
        }
    }

    public function getCloudflareZonesEndpoint()
    {
        if (null === $this->zonesEndpoint) {
            $this->zonesEndpoint = new Zones(
                new Guzzle(
                    new APIKey($this->email, $this->key)
                )
            );
        }

        return $this->zonesEndpoint;
    }
}
