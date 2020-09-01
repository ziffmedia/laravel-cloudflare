<template>
    <clear-cache-button></clear-cache-button>
</template>

<script>
import ClearCacheButton from './ClearCacheButton'

export default {
    components: {
        'clear-cache-button': ClearCacheButton
    },
    props: ['resourceName', 'field'],
}
</script>
