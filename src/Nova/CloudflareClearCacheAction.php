<?php

namespace ZiffMedia\LaravelCloudflare\Nova;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use ZiffMedia\LaravelCloudflare\Eloquent\HasCloudflareTagging;

class CloudflareClearCacheAction extends Action
{
    public $name = 'Cloudflare: Clear Cache';

    public function handle(ActionFields $fields, Collection $models)
    {
        $tags = collect();

        /** @var Model|HasCloudflareTagging $model */
        foreach ($models as $model) {
            $tags = $tags->push($model->cloudflareTag());
        }

        cloudflare()->purgeCacheByCacheTags($tags->toArray());

        return Action::message('Cache cleared for '.$tags->count().' tags: '.$tags->implode(', '));
    }

    public function fields(NovaRequest $request)
    {
        $tags = $request->findModelQuery($request->get('resources'))
            ->get()
            ->filter(fn (Model $model) => method_exists($model, 'cloudflareTag'))
            ->map(fn (Model $model) => $model->cloudflareTag());

        return [
            Text::make('Tags')
                ->default(fn () => $tags->implode(', '))
                ->readonly()
                ->help('These tags will be cleared from the Cloudflare cache.'),
        ];
    }
}
