<template>
    <panel-item :field="field">
        <template slot="value">
            <clear-cache-button></clear-cache-button>
        </template>
    </panel-item>
</template>

<script>
import ClearCacheButton from './ClearCacheButton'

export default {
    components: {
        'clear-cache-button': ClearCacheButton
    },
    props: ['resource', 'resourceName', 'resourceId', 'field'],
}
</script>
