<?php

namespace ZiffMedia\LaravelCloudflare\Eloquent;

use Illuminate\Support\Str;

trait HasCloudflareTagging
{
    public function cloudflareTag(): string
    {
        $name = Str::of(get_class($this))
            ->classBasename()
            ->plural()
            ->snake('-')
            ->toString();

        return $name.'-'.$this->getKey();
    }
}
