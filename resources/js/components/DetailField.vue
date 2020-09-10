<template>
    <panel-item :field="field">
        <template slot="value">
            <clear-cache-button
                :urls="field.urls"
                :tags="field.tags"
            ></clear-cache-button>
        </template>
    </panel-item>
</template>

<script>
import ClearCacheButton from './ClearCacheButton'

export default {
    components: {
        'clear-cache-button': ClearCacheButton
    },
    props: ['field'],
}
</script>
