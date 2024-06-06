<?php

namespace ZiffMedia\LaravelCloudflare\Eloquent;

use Illuminate\Support\Str;

trait HasCloudflareTagging
{
    public function cloudflareTag(): string
    {
        $name = Str::of(get_called_class())
            ->classBasename()
            ->plural()
            ->snake('-')
            ->toString();

        return $name . '-' . $this->getKey();
    }

    public function cloudflareTagsToClear(): array
    {
        return [
            $this->cloudflareTag(),
        ];
    }
}
