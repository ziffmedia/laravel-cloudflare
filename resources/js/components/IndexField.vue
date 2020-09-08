<template>
    <clear-cache-button
        label=""
        :urls="field.urls"
        :tags="field.tags"
    ></clear-cache-button>
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
