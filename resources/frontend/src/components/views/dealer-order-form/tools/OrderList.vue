<template>

    <div class="row" v-if="orders.length > 0">
        <div class="col-md-12 col-xs-12">
            <h4 class="text-center">Available orders</h4>
            <ul class="list-group order-list-container">

                <li class="list-group-item order-list-item" v-for="order in orders">
                    <div class="order-list-desc">
                        <h5>
                            <strong>{{ order.updatedAt }}</strong>
                            <order-status-label :status="order.status"></order-status-label>
                        </h5>

                        <h6 v-if="order.orderReference">
                            <span v-show="order.orderReference.customerName">{{ order.orderReference.customerName }}</span>
                            <span v-show="order.orderReference.email">, {{ order.orderReference.email }}</span>
                        </h6>

                        <h5>
                            <span v-if="order.dealerNotes">{{ order.dealerNotes }}</span>
                            <span else>&nbsp;</span>
                        </h5>
                    </div>
                    <div>
                        <button type="button"
                                class="btn btn-primary"
                                v-on:click="load(order)"
                                v-bind:disabled="loading">
                            Load
                        </button>
                    </div>
                </li>

            </ul>

        </div>
    </div>

</template>

<script type="text/babel">
    import OrderStatusLabel from 'src/components/views/partials/OrderStatusLabel.vue'
    import {mapActions, mapGetters} from 'vuex'

    export default {
        components: {
            OrderStatusLabel
        },
        data() {
            return {
            }
        },
        props: {
            orders: {
                type: Array,
                default: []
            }
        },
        computed: {
            ...mapGetters({
                orderState: 'dealerOrderForm/orderState',
                getUiToolsStateLoadForm: 'dealerOrderForm/uiTools/getUiToolsStateLoadForm'
            }),
            loading() { return (this.orderState.sync.status === 'loading' && this.getUiToolsStateLoadForm !== 'new') }
        },
        methods: {
            ...mapActions({
                loadDealerOrder: 'dealerOrderForm/loadDealerOrder',
                updateOrderSync: 'dealerOrderForm/updateOrderSync',
                uiToolsSetStateLoadForm: 'dealerOrderForm/uiTools/uiToolsSetStateLoadForm'
            }),
            load(order) {
                let self = this
                this.loadDealerOrder({
                    payload: {
                        id: order.uuid
                    },
                    beforeCb() {
                        self.$parent.run({text: 'Loading..', type: 'form'})
                        self.uiToolsSetStateLoadForm('idle')
                    },
                    successCb(response) {
                        self.$nextTick(function () {
                            self.$parent.$emit('data-process-update', {
                                running: false,
                                success: 'Order successfully loaded.'
                            })
                            self.updateOrderSync({merging: 'done'})
                            self.$bus.$emit('dofOrderLoaded')
                        })
                    },
                    errorCb(response) {
                        self.$parent.$emit('data-failed', response)
                    }
                })
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>

    .order-list-container {
        max-height: 300px;
        overflow: auto;

        .order-list-item {
            vertical-align: middle;
            display: table;
            width: 100%;

            >div {
                  vertical-align: middle;
                  display: table-cell;
            }
            .order-list-desc {
                  width: 100%;
            }
        }

        .order-list-item:hover {
            background-image: linear-gradient(to bottom,#f5f5f5 0,#eee 100%);
        }
    }

</style>