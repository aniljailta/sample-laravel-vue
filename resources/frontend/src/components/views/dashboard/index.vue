<template>
    <div class="row">
        <!-- <div class="col-xs-12 col-sm-12">
            <count-stats></count-stats>
        </div>
        <div class="col-xs-12 col-sm-12">
            <label>Items Pending <i class="fa fa-hand-o-down" aria-hidden="true"></i></label>
            <pending-counts></pending-counts>
        </div> -->
        <div class="col-xs-12">
            <pending-items></pending-items>
        </div>

        <div class="col-xs-12 col-sm-6">
            <chart></chart>
        </div>

        <div class="col-xs-12 col-sm-6">
            <pchart></pchart>
        </div>

        <modal-orders v-if="modal !== null && modal.mode === 'orders' " :show="true"
                      v-on:close="closeModalOrders"
                      :item="modal !== null && modal.mode === 'orders' ? modal.item : null">
        </modal-orders>

        <modal-sales v-if="modal !== null && modal.mode === 'sales' " :show="true"
                     v-on:close="closeModalSales"
                     :item="modal !== null && modal.mode === 'sales' ? modal.item : null">
        </modal-sales>
    </div>
</template>

<script type="text/babel">
    import chart from './Chart/chart.vue'
    import pchart from './Chart/pchart.vue'
    import countStats from './Chart/counts.vue'
    import pendingCounts from './Chart/pcounts.vue'
    import pendingItems from './Chart/pendingItems'

    import ModalOrders from './modals/ModalOrders.vue'
    import ModalSales from './modals/ModalSales.vue'

    export default {
        data() {
            return {
                modal: null
            }
        },
        components: {
            chart,
            pchart,
            countStats,
            pendingCounts,
            pendingItems,
            ModalOrders,
            ModalSales
        },
        methods: {
            closeModalOrders() {
                this.modal = null
            },
            closeModalSales() {
                this.modal = null
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>
    .iframe {
    }
</style>