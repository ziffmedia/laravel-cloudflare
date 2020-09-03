<?php

namespace ZiffMedia\LaravelCloudflare\Fields;

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
     * @param array $urls
     * @return $this
     */
    public function purgeUrls(array $urls)
    {
        return $this->withMeta(['urls' => $urls]);
    }
}
