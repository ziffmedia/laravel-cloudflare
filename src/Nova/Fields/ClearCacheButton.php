<?php

namespace ZiffMedia\LaravelCloudflare\Nova\Fields;

use Laravel\Nova\Fields\Field;

class ClearCacheButton extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'cache-clear';

    /**
     * Set the urls to be purged when button is clicked.
     *
     * @param  array|callable  $urls
     * @return $this
     */
    public function purgeUrls($urls)
    {
        $urls = is_callable($urls) ? call_user_func($urls, $this) : $urls;

        return $this->withMeta(['urls' => $urls]);
    }

    /**
     * Set the header tags to be purged when button is clicked.
     *
     * @param  array|callable  $urls
     * @return $this
     */
    public function purgeTags($tags)
    {
        $tags = is_callable($tags) ? call_user_func($tags, $this) : $tags;

        return $this->withMeta(['tags' => $tags]);
    }
}
