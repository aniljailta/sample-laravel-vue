<template>
    <span>Current Timezone: {{ timezone.value }}</span>
</template>

<script type="text/babel">
    import {mapGetters} from 'vuex'

    export default {
        data() {
            return {}
        },
        components: {},
        computed: {
            ...mapGetters({
                'timezone': 'settings/timezone'
            })
        }
    }
</script>

<style type="text/css">

</style>