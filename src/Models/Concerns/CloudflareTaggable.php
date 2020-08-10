<?php

namespace ZiffDavis\Laravel\Cloudflare\Models\Concerns;

use Illuminate\Support\Str;

trait CloudflareTaggable
{
    public function cloudflareTag()
    {
        return Str::slug($this->getTable()) . '_' . $this->getKey();
    }
}
