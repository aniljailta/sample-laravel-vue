<template>

    <div class="panel panel-defaul order-state" v-if="orderCurrent.id">

        <div class="panel-body">
            <note-admin ref="noteAdmin" class="col-xs-12"
                        v-if="orderCurrent.noteAdmin"
                        :note="orderCurrent.noteAdmin"></note-admin>

            <div class="col-xs-12 col-sm-12">
                <h5 v-if="orderCurrent.updatedAt">
                    <i class="fa fa-clock-o" aria-hidden="true"></i> {{ orderCurrent.updatedAt }}
                    <order-status-label :status="orderCurrent.status"></order-status-label>
                </h5>
                <h5 v-if="orderCurrent.dealerNotes">
                    <i class="fa fa-sticky-note" aria-hidden="true"></i> {{ orderCurrent.dealerNotes }}
                </h5>
            </div>
            <div class="col-xs-12 col-sm-12" v-if="paymentType === 'rto' && orderCurrent.statusId === 'signature_pending'">
                <div class="alert alert-warning">
                    Rent-to-own orders require a signature from the rent-to-own company prior to submittal.
                    You will receive an email when the order is signed and ready to be submitted.
                </div>
            </div>
        </div>
    </div>

</template>

<script type="text/babel">
    import OrderStatusLabel from 'src/components/views/partials/OrderStatusLabel.vue'
    import NoteAdmin from './NoteAdmin.vue'
    import {mapGetters} from 'vuex'

    export default {
        data() {
            return {
                dataProcess: {
                    type: 'form',
                    running: false
                }
            }
        },
        components: {OrderStatusLabel, NoteAdmin},
        props: {
            orderCurrent: {
                type: Object,
                default() {
                    return {
                        id: null
                    }
                },
                required: true
            }
        },
        computed: {
            ...mapGetters({
                paymentType: 'dealerOrderForm/orderPaymentType'
            })
        },
        methods: {}
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>
    .panel-body {
        padding: 0px;
    }

    .alert.alert-warning {
        text-align: center;
    }
</style>