<template>

    <tr :class="trClass">
        <td class="text-center">{{ commissionData.order.id}}</td>
        <td class="text-center">{{ filters.moment(commissionData.order.dateSubmitted, 'YYYY-MM-DD', 'MM/DD/YYYY') }}</td>
        <td class="text-center">{{ filters.moment(commissionData.order.updatedAt, 'YYYY-MM-DD HH:mm:ss', 'MM/DD/YYYY') }}</td>
        <td class="text-center">
            <order-status-label :status="commissionData.order.status"/>
        </td>
        <td class="text-center">{{ commissionData.dealer.businessName }}</td>
        <td class="text-center">{{ commissionData.order.orderReference.customerName }}</td>
        <td class="text-center nowrap">
            <router-link :to="{ name: 'building.show', params: { id: commissionData.order.buildingId }}" target="_blank">
                {{ commissionData.order.building.serialNumber }}
            </router-link>
        </td>
        <td class="text-center nowrap">
            <span class="money-cell">
                {{ commissionData.order.building ? filters.money(commissionData.order.building.totalPrice) : '' }}
            </span>
        </td>
        <td class="text-center">
            <div v-if="isModifyEnabled">
                <div class="btn-group">
                    <div class="form-group">
                        <div class="input-group input-group-xs">
                            <input class="form-control input-xs"
                                   :disabled="!isEditable"
                                   @blur="applyCommissionRate($event.target.value)"
                                   :value="commissionData.commissionRate"
                                   type="number">
                            <span class="input-group-addon">%</span>
                        </div>
                    </div>
                </div>
            </div>
            <div v-else>
                {{ commissionData.commissionRate }}%
            </div>
        </td>
        <td class="text-center">
            <span class="money-cell">{{ filters.money(commissionAmount) }}</span>
        </td>
        <td class="text-center">
            <span class="money-cell">
                {{ filters.money(commissionData.dealerDiscount) }}
            </span>
        </td>
        <td class="text-center">
            <div v-if="isModifyEnabled">
                <div class="btn-group">
                    <div class="form-group">
                        <div class="input-group input-group-xs">
                            <span class="input-group-addon">$</span>
                            <input class="form-control input-xs" style="min-width: 80px;"
                                   @blur="applyAmountDue($event.target.value)"
                                   :disabled="!isEditable"
                                   :value="commissionData.amountDue"
                                   type="number">
                        </div>
                    </div>
                </div>
            </div>
            <div v-else>
                <span class="money-cell">
                    {{ filters.money(commissionData.amountDue) }}
                </span>
            </div>
        </td>
    </tr>

</template>

<script type="text/babel">
    /*global swal*/
    import OrderStatusLabel from 'src/components/views/partials/OrderStatusLabel.vue'
    import apiDealerCommission from 'src/api/dealer-commission'

    export default {
        data() {
            return {
                isEditable: true,
                trClass: ''
            }
        },
        props: {
            commissionData: {
                default() {
                    return {}
                }
            },
            isModifyEnabled: {
                default: false
            }
        },
        components: {OrderStatusLabel},
        computed: {
            commissionAmount() {
                return this.commissionData.order.building.totalPrice * (this.commissionData.commissionRate / 100)
            }
        },
        methods: {
            applyChanges(data) {
                this.isEditable = true
                this.trClass = ''
                this.$emit('update:commission-data', data)
                this.$nextTick(() => {
                    this.trClass = 'changed-highlight'
                    setTimeout(() => {
                        this.trClass = ''
                    }, 1000)
                })
            },
            applyAmountDue(value) {
                apiDealerCommission.updateAmountDue({
                    id: this.commissionData.id,
                    amountDue: value
                }).then(response => {
                    this.applyChanges(response.data)
                }).catch(response => {
                    this.showMessage(response.data)
                })
            },
            applyCommissionRate(value) {
                apiDealerCommission.updateCommissionRate({
                    id: this.commissionData.id,
                    commissionRate: value
                }).then(response => {
                    this.applyChanges(response.data)
                }).catch(response => {
                    this.showMessage(response.data)
                })
            },
            showMessage(data) {
                let message = _.isObject(data) ? _.values(data) : data
                let error = _.isArray(message) ? message.join('\r\n') : message
                swal({
                    title: 'Error',
                    text: error,
                    type: 'error',
                    confirmButtonColor: '#DD6B55',
                    confirmButtonText: 'Close'
                })
                this.isEditable = true
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>
    .changed-highlight {
        background-color: #fffdf1 !important;

        transition: all 0.5s easeInOutQuart !important;
        /*background-color: transparent;*/
    }
</style>