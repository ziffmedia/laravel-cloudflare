<?php

namespace ZiffMedia\LaravelCloudflare\Models\Concerns;

use Illuminate\Support\Str;

trait CloudflareTaggable
{
    public function cloudflareTag()
    {
        return Str::slug($this->getTable()).'_'.$this->getKey();
    }

    public function cloudflareTagsToClear(): array
    {
        $tagsToClear = [
            $this->cloudflareTag(),
        ];

        return $tagsToClear;
    }
}
