<template>

    <div class="fh">
        <dealer-map :show="showMap" :dealers="dealers"></dealer-map>
    </div>

</template>

<script type="text/babel">
    import DataItem from 'src/components/views/_base/Block/DataItem.vue'
    import DealerMap from './dealer-map/Map.vue'
    import apiDealers from 'src/api/dealers'

    export default {
        name: 'dealer-map-page',
        extends: DataItem,
        data() {
            return {
                dealers: [],
                showMap: false
            }
        },
        components: {
            DealerMap
        },
        methods: {
            initData() {
                apiDealers.get({
                    query: {
                        include: ['location'],
                        where: {
                            is_active: 'yes'
                        },
                        per_page: 9999
                    }
                }).then((response) => {
                    this.dealers = response.data.data
                    this.$emit('data-ready')
                }).catch(response => {
                    this.$emit('data-failed', response)
                })
            },
            dataAllReady() {
                this.showMap = true
            }
        }
    }
</script>

<style type="text/sass" lang="scss" rel="stylesheet/scss">


</style>