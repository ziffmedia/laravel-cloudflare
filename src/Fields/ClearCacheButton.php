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
}
