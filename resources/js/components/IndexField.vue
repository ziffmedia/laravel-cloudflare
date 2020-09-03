<template>
    <clear-cache-button
        label=""
        :urls="field.urls"
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
