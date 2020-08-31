<template>
    <panel-item :field="field">
        <template slot="value">
            test
        </template>
    </panel-item>
</template>

<script>
export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],
}
</script>
