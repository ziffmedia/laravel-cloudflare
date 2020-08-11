<?php

namespace ZiffMedia\LaravelCloudflare;

use Cloudflare\API\Adapter\Guzzle;
use Cloudflare\API\Auth\APIKey;
use Cloudflare\API\Endpoints\Zones;
use Illuminate\Support\Collection;

class Cloudflare
{

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

    public function purgeCacheByUrl($urls)
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
