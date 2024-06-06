<?php

namespace ZiffMedia\LaravelCloudflare\Nova;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use ZiffMedia\LaravelCloudflare\Eloquent\HasCloudflareTagging;

class CloudflareClearCacheAction extends Action
{
    public $name = 'Cloudflare: Clear Cache';

    public function handle(ActionFields $fields, Collection $models)
    {
        $tags = collect();

        /** @var Model|HasCloudflareTagging $model */
        foreach ($models as $model) {
            $tags = $tags->merge($model->cloudflareTagsToClear());
        }

        cloudflare()->purgeCacheByCacheTags($tags->toArray());

        return $models;
    }
}
