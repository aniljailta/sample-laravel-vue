<template>

    <div class="form-horizontal">
        <data-process v-bind:process="dataProcess" v-bind:with_loader="true"></data-process>

        <form v-if="dataIsReady">
            <div class="form-group">
                <div class="row col-xs-12" v-if="curItem.id">
                    <div class="col-xs-12 col-md-3">
                        <label for="status_id" class="control-label">Status</label>
                        <select id="status_id"
                                name="status_id"
                                class="form-control"
                                initial="off"
                                v-model="curItem.statusId">
                        <option v-for="(status, status_id) in statuses"
                                v-bind:value="status.id"
                                v-bind:selected="curItem.statusId == status.id">
                            {{ status.title }}
                        </option>
                        </select>
                    </div>
                    <div class="col-xs-12 col-md-3">
                        <label for="date_created" class="control-label">Date Created</label>
                        <div id="date_created">
                            {{ curItem.createdAt }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3">
                        <label for="offer_id" class="control-label">Order ID</label>
                        <div id="offer_id">
                            # {{ curItem.orderId }}
                        </div>
                    </div>
                </div>
                <div class="row col-xs-12">
                    <div class="col-xs-12 col-md-3" v-if="curItem.id">
                        <label for="dealer" class="control-label">Dealer</label>
                        <div id="dealer">
                            {{ curItem.order.dealer.businessName }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3" v-if="curItem.id && item.building !== null">
                        <label for="serial_number" class="control-label">Serial Number</label>
                        <div id="serial_number">
                            <router-link :to="{ name: 'building.show', params: { id: item.building.id }}" target="_blank">
                                {{ item.building.serialNumber }}
                            </router-link>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3" v-if="curItem.id">
                        <label for="retail" class="control-label">Retail</label>
                        <div id="retail" style="color: black">
                            {{ filters.money(curItem.order.totalSalesPrice) }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3">
                        <label for="invoice_id" class="control-label">Invoice #</label>
                        <input type="text" class="form-control" placeholder="" name="invoice_id" id="invoice_id" v-model="curItem.invoiceId">
                    </div>
                </div>

                <!-- Order -->
                <div class="row col-xs-12 col-sm-4">
                    <div class="col-xs-12" v-if="curItem.id">
                        <label for="customer" class="control-label">Customer</label>
                        <div id="customer">
                            <dl class="">
                                <dt>Name</dt><dd>{{ curItem.order.orderReference.customerName }}</dd>
                                <dt>Phone</dt><dd>{{ curItem.order.orderReference.phoneNumber }}</dd>
                                <dt>Email</dt><dd>
                                {{ curItem.order.orderReference.email }}
                                <button type="button" class="btn btn-default btn-xs" v-on:click="sendEmailModal = true">
                                    <i class="fa fa-envelope" aria-hidden="true"></i> Send Notification
                                </button>
                            </dd>
                                <dt>Location</dt>
                                <dd>
                                    {{ curItem.order.orderReference.address }}
                                    {{ curItem.order.orderReference.city }}
                                    {{ curItem.order.orderReference.state }}
                                    {{ curItem.order.orderReference.zip }}
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="row col-xs-12 col-sm-4">
                    <div class="col-xs-12" v-if="curItem.id && curItem.building !== null">
                        <label for="building" class="control-label">Building</label>
                        <div id="building">
                            <dl class="">
                                    <dt>Status</dt><dd>?</dd>
                                    <dt>Location</dt><dd>{{ curItem.building.lastLocation.location.name }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
                <div class="row col-xs-12 col-sm-4">
                    <div class="col-xs-12" v-if="curItem.id && curItem.delivery">
                        <label for="delivery" class="control-label">Delivery</label>
                        <div id="delivery">
                            <dl class="">
                                <dt>Status</dt>
                                <dd>
                                    <template v-if="curItem.delivery">
                                        <delivery-status-label :status="curItem.delivery.status"></delivery-status-label>
                                    </template>
                                </dd>

                                <dt>Location</dt>
                                <dd>
                                    <template v-if="curItem.delivery">
                                        {{ curItem.delivery.endLocation.name }}
                                    </template>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>

                <button type="button"
                        :style="{marginTop: '26px'}"
                        class="btn btn-primary btn-sm"
                        v-bind:disabled="!item.order.files || item.order.files.length === 0"
                        v-on:click="showAttachments">

                    <i class="fa fa-files-o" aria-hidden="true"></i>
                    <span class="label label-default">
                            {{ item.order.files ? item.order.files.length : 0 }}
                        </span>

                </button>

                <div class="row col-xs-12">
                    <div class="col-xs-12 col-md-12">
                        <label for="notes-dealer" class="control-label">Dealer Notes</label>
                        <div id="notes-dealer">
                            <em v-if="curItem.order.noteDealer">{{ curItem.order.noteDealer }}</em>
                            <em v-else>&lt;none&gt;</em>
                        </div>
                    </div>
                </div>

                <div class="row col-xs-12">
                    <div class="col-xs-12 col-md-12">
                        <label for="notes-admin" class="control-label">Admin Notes</label>
                        <textarea class="form-control"
                                  placeholder=""
                                  name="notes"
                                  id="notes-admin"
                                  v-model="curItem.noteAdmin">
                        </textarea>
                    </div>
                </div>
            </div>
        </form>

        <component v-if="sendEmailModal"
                   is="ModalSendEmail"
                   v-bind:item="item"
                   v-on:close="closeModalSendEmail"
                   v-bind:show="sendEmailModal">
        </component>
    </div>

</template>

<script type="text/babel">
    import BaseDataItem from 'src/components/views/_base/Block/DataItem.vue'
    import DeliveryStatusLabel from 'src/components/views/partials/DeliveryStatusLabel.vue'

    import apiSales from 'src/api/sales'

    export default {
        name: 'sale-form-item',
        extends: BaseDataItem,
        data() {
            return {
                sendEmailModal: false,
                statuses: {},
                curItem: {}
            }
        },
        components: {
            DeliveryStatusLabel,
            ModalSendEmail: function(resolve) {
                require(['../modals/ModalSendEmail.vue'], resolve)
            }
        },
        computed: {
            id() {
                if (!_.isUndefined(this.item.id)) {
                    return this.item.id
                }
                return null
            }
        },
        methods: {
            save({ item }) {
                return apiSales.saveSale({ item }).then(response => response.data)
            },
            submit() {
                let form = _.merge(this.curItem, {})

                this.run({text: 'Saving..', type: 'form'})
                return this.save({ item: form })
                    .then(data => {
                        this.$emit('data-process-update', {
                            running: false,
                            success: data
                        })
                        this.$emit('item-saved')
                    })
                    .catch(response => {
                        this.$emit('data-failed', response)
                    })
            },
            initData() {
                if (this.id) {
                    apiSales.get({
                        id: this.id,
                        query: {
                            include: [
                                'order.order_reference',
                                'order.files',
                                'order.sale',
                                'order.sale.order',
                                'order.sale.order.files',
                                'order.dealer',
                                'building',
                                'building.last_location.location',
                                'location',
                                'delivery.end_location'
                            ]
                        }
                    })
                        .then(response => {
                            return this.initDependencies().then(() => {
                                return response
                            })
                        })
                        .then(response => {
                            let item = response.data
                            this.curItem = _.cloneDeep(item)
                            if (this.curItem.order) {
                                this.$set(this.curItem, 'noteAdmin', this.curItem.order.noteAdmin)
                            }
                        })
                        .then(() => {
                            this.$emit('data-ready')
                        })
                        .catch(response => {
                            this.$emit('data-failed', response)
                        })
                } else {
                    this.initDependencies()
                        .then(response => { this.$emit('data-ready') })
                        .catch(response => { this.$emit('data-failed', response) })
                }
            },
            initDependencies() {
                const datas = [
                    apiSales.statuses()
                ]

                return Promise.all(datas)
                    .then(response => {
                        this.statuses = response[0].data
                        return response
                    })
            },
            closeModalSendEmail() {
                this.sendEmailModal = false
            },
            showAttachments() {
                this.$parent.$parent.$parent.$emit('change-entry', {
                    mode: 'attachments',
                    item: this.$parent.$parent.$parent.modal.mode === 'editSale' ? this.curItem.order : this.curItem.order.sale
                })
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>
    .datepicker{
        position: relative;
        display: block !important;
        padding: 0;
    }
</style>