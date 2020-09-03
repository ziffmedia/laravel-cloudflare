<template>
    <default-field :field="field">
        <template slot="field">
            <clear-cache-button></clear-cache-button>
        </template>
    </default-field>
</template>

<script>
import ClearCacheButton from './ClearCacheButton'

export default {
    components: {
        'clear-cache-button': ClearCacheButton
    },
    props: ['field']
}
</script>
