<template>

    <div class="form-horizontal">
        <data-process v-bind:process="dataProcess" v-bind:with_loader="true"></data-process>
        <div v-if="curItem.originalOrder !== null && curItem.originalOrder !== undefined"
             class="alert alert-warning" role="alert">
            <p>
                <span class="col-md-12 col-xs-12 text-center"><strong>This is a change order!</strong></span>
                Be sure that building has not been started before generating a sale.<br/>
                Generating a sale for this Change Order will cancel the original order and update the sale and building. If generating a sale, it is best to check with manufacturing to be sure that building has not been started. Cancelling this Change Order will maintain the original order.
            </p>
        </div>
        <form v-if="dataIsReady">
            <div class="form-group">
                <div class="row col-xs-12" v-if="curItem.originalOrder">
                    <div class="col-xs-12 col-md-6">
                        <label for="original-order-id" class="control-label">Original Order ID:</label>
                        <div id="original-order-id">
                            <span>#{{ curItem.originalOrderId }}</span>
                        </div>
                    </div>
                    <div class="clearfix hidden-md visible-xs">&nbsp;</div>
                    <div class="col-xs-12 col-md-6">
                        <label for="original-order-total-amount" class="control-label">Original Order Total Amount:</label>
                        <div id="original-order-total-amount">
                            <span>{{ curItem.originalOrder.totalAmount | money }}</span>
                        </div>
                    </div>
                </div>
                <div class="row col-xs-12">
                    <div class="col-xs-12 col-md-6" v-if="curItem.totalAmount">
                        <label for="order-total-amount" class="control-label">Order Total:</label>
                        <div id="order-total-amount">
                            <span>{{ curItem.totalAmount | money }}</span>
                        </div>
                    </div>
                    <div class="clearfix hidden-md visible-xs">&nbsp;</div>
                    <div class="col-xs-12 col-md-6" v-if="curItem.sale">
                        <label for="sale-id" class="control-label">Sale ID:</label>
                        <div id="sale-id">
                            <span>#{{ curItem.sale.id }}</span>
                        </div>
                    </div>
                </div>

                <div class="row col-xs-12">
                    <div class="col-xs-12 col-md-6"  v-if="curItem.building && curItem.building.serialNumber">
                        <label for="building-serial-number" class="control-label">Building Serial Number:</label>
                        <div id="building-serial-number">
                            <span>{{ curItem.building.serialNumber }}</span>
                        </div>
                    </div>
                    <div class="clearfix hidden-md visible-xs">&nbsp;</div>
                    <div class="col-xs-12 col-md-6"  v-if="curItem.building && curItem.building.lastStatus">
                        <label for="building-status" class="control-label">Building Status:</label>
                        <div id="building-status">
                            <building-status-label :status="curItem.building.lastStatus.buildingStatus" v-if="curItem.building.lastStatus"></building-status-label>
                        </div>
                    </div>
                </div>

                <div class="row col-xs-12" v-if="curItem.uuid">
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
                        <button type="button"
                                :style="{marginTop: '26px'}"
                                class="btn btn-primary btn-sm"
                                v-bind:disabled="!lastItem.files || lastItem.files.length === 0"
                                v-on:click="showAttachments">

                            <i class="fa fa-files-o" aria-hidden="true"></i>
                            <span class="label label-default">
                            {{ lastItem.files ? lastItem.files.length : 0 }}
                        </span>

                        </button>

                        <button type="button" v-if="lastItem.statusId === 'sale_generated'"
                                :style="{marginTop: '26px'}"
                                class="btn btn-primary btn-sm"
                                @click="openOrderDocuments">
                            <i class="fa fa-file-pdf-o" aria-hidden="true"></i> Order Documents
                        </button>
                    </div>
                </div>

                <div class="row col-xs-12">
                    <div class="col-xs-12 col-md-12">
                        <label for="notes-dealer" class="control-label">Dealer Notes</label>
                        <div id="notes-dealer">
                            <em v-if="curItem.noteDealer">{{ curItem.noteDealer }}</em>
                            <em v-else>&lt;none&gt;</em>
                        </div>
                    </div>
                </div>

                <div class="row col-xs-12">
                    <div class="col-xs-12 col-md-12">
                        <label for="notes-admin" class="control-label">Admin Notes</label>
                        <textarea class="form-control" placeholder="" name="notes" id="notes-admin" v-model="curItem.noteAdmin"></textarea>
                    </div>
                </div>
            </div>
        </form>
    </div>

</template>

<script type="text/babel">
    import BaseDataItem from 'src/components/views/_base/Block/DataItem.vue'
    import apiOrders from 'src/api/orders'

    import BuildingStatusLabel from 'src/components/views/partials/BuildingStatusLabel.vue'

    export default {
        name: 'order-form-item',
        extends: BaseDataItem,
        data() {
            return {
                statuses: {},
                lastItem: {},
                curItem: {}
            }
        },
        components: {
            BuildingStatusLabel
        },
        methods: {
            save({ item, data }) {
                return apiOrders.saveOrder({ item, data }).then(response => response.data)
            },
            submit() {
                let self = this
                let form = _.pick(this.curItem, ['id', 'uuid', 'noteAdmin', 'noteDealer', 'statusId'])
                form.id = form.uuid // TODO: change to ID back (and change api)?
                this.run({text: 'Saving..', type: 'form'})
                return this.save({ item: form })
                    .then(data => {
                        self.initData(false).then(() => {
                            self.$emit('data-process-update', {
                                running: false,
                                success: data
                            })
                            self.$emit('item-saved')
                        })
                    })
                    .catch(response => {
                        self.$emit('data-failed', response)
                    })
            },
            initData(withDependencies = true) {
                if (this.item.id) {
                    return apiOrders.get({
                        id: this.item.id,
                        query: {
                            include: [
                                'building.last_status.building_status',
                                'original_order',
                                'files'
                            ]
                        }
                    })
                        .then(response => {
                            if (!withDependencies) return response

                            return this.initDependencies().then(() => {
                                return response
                            })
                        })
                        .then(response => {
                            let item = response.data
                            this.$set(this, 'lastItem', _.cloneDeep(item))
                            this.$set(this, 'curItem', _.cloneDeep(item))
                        })
                        .then(() => {
                            this.$emit('data-ready')
                        })
                        .catch(response => {
                            this.$emit('data-failed', response)
                        })
                } else {
                    return this.initDependencies()
                        .then(response => { this.$emit('data-ready') })
                        .catch(response => { this.$emit('data-failed', response) })
                }
            },
            initDependencies() {
                const datas = [
                    apiOrders.statuses()
                ]

                return Promise.all(datas)
                    .then(response => {
                        this.statuses = response[0].data
                        return response
                    })
            },
            showAttachments() {
                this.$parent.$parent.$parent.$emit('change-entry', {
                    mode: 'attachments',
                    item: this.item
                })
            },
            openOrderDocuments() {
                let url = this.$url(apiOrders.actions.orderDocuments.url, {
                    id: this.item.uuid
                })
                window.open(url, '_blank')
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss">
</style>