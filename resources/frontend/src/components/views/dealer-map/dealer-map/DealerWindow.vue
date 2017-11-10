<template>

    <div>
        <h5>{{ item.title }}</h5>
        <h6>{{ item.name }}</h6>
        <span>
            <i class="fa fa-map-marker" aria-hidden="true"></i> {{ item.location.address }}, {{ item.location.city }}, {{ item.location.state }} {{ item.location.zip }}, {{ item.location.country }} <br>
            <i class="fa fa-envelope" aria-hidden="true"></i> {{ item.dealer.email }} <br>
            <i class="fa fa-phone" aria-hidden="true"></i> {{ item.dealer.phone }}
        </span>
    </div>
</template>

<script type="text/babel">
    export default {
        data () {
            return {
            }
        },
        props: {
            item: {
                type: Object,
                required: true,
                default() {
                    return {}
                }
            }
        },
        methods: {}
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>
</style>