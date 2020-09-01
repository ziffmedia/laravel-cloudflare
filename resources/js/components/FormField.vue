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

    props: ['resourceName', 'resourceId', 'field'],

    methods: {
        /*
         * Set the initial, internal value for the field.
         */
        setInitialValue() {
            this.value = this.field.value || ''
        },

        /**
         * Fill the given FormData object with the field's internal value.
         */
        fill(formData) {
            formData.append(this.field.attribute, this.value || '')
        },

        /**
         * Update the field's internal value.
         */
        handleChange(value) {
            this.value = value
        },
    },
}
</script>
