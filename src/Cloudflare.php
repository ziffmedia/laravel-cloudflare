<?php

namespace ZiffMedia\LaravelCloudflare;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;
use ZiffMedia\LaravelCloudflare\Eloquent\HasCloudflareTagging;

class Cloudflare
{
    protected array $responseCacheTags = [];

    public function addResponseTaggable($taggable): static
    {
        Collection::wrap($taggable)
            ->map(fn ($taggable) => $this->convertTaggableToTag($taggable))
            ->each(fn ($tag) => $this->addResponseCacheTag($tag));

        return $this;
    }

    public function addResponseCacheTag(string $tag): static
    {
        $this->responseCacheTags[] = strtolower($tag);

        return $this;
    }

    public function getResponseCacheTags(): array
    {
        return $this->responseCacheTags;
    }

    public function setResponseCacheTags(array $responseCacheTags): static
    {
        $this->responseCacheTags = $responseCacheTags;

        return $this;
    }

    public function purgeCacheByTaggable($taggable): static
    {
        $tags = Collection::wrap($taggable)
            ->map(fn ($taggable) => $this->convertTaggableToTag($taggable))
            ->toArray();

        $this->purgeCacheByCacheTags($tags);

        return $this;
    }

    public function purgeCacheByCacheTags(string|array $cacheTags): static
    {
        $cacheTags = Arr::wrap($cacheTags);

        if (! config('cloudflare.purge_enabled')) {
            logger()->info('Cloudflare::purgeCacheByCacheTags requested but purge is not enabled, purging tags: '.implode(',', $cacheTags));

            return $this;
        }

        collect($cacheTags)->chunk(25)->each(
            fn (Collection $chunk) => Http::cloudflare()->post('/purge_cache', ['tags' => array_values($chunk->toArray())])
        );

        return $this;
    }

    public function purgeCacheByUrls(array $urls): static
    {
        if (! config('cloudflare.purge_enabled')) {
            logger()->info('Cloudflare::purgeCacheByUrls requested but purge is not enabled, urls: '.implode(', ', $urls));

            return $this;
        }

        Http::cloudflare()->post('/purge_cache', ['prefixes' => $urls]);

        return $this;
    }

    protected function convertTaggableToTag($taggable): string
    {
        if (is_string($taggable)) {
            return strtolower($taggable);
        }

        if ($taggable instanceof Model) {
            $traits = class_uses($taggable);

            if (isset($traits[HasCloudflareTagging::class])) {
                return $taggable->cloudflareTag();
            }

            return strtolower(Str::slug($taggable->getTable()).'-'.$taggable->getKey());
        }

        throw new RuntimeException("Provided ($taggable) could not be converted to a Cloudflare Cache-Tag string");
    }
}
