<template>

    <div class="form-horizontal">
        <data-process v-bind:process="dataProcess" v-bind:with_loader="true"></data-process>

        <form v-if="dataIsReady">
            <div class="form-group">
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
                                v-bind:disabled="!item.files || item.files.length === 0"
                                v-on:click="showAttachments">

                            <i class="fa fa-files-o" aria-hidden="true"></i>
                            <span class="label label-default">
                            {{ item.files ? item.files.length : 0 }}
                        </span>

                        </button>
                    </div>
                    <div class="col-xs-12 col-md-3" v-if="curItem.statusId === 'sale_generated'">
                        <label class="control-label">&nbsp;</label>
                        <div class="checkbox">
                            <input id="generate_sale"
                                   name="generate_sale"
                                   type="checkbox"
                                   value="1"
                                   v-model="curItem.generateSale"
                                   v-bind:true-value="1"
                                   v-bind:false-value="0"
                                   v-bind:checked="curItem.statusId !== 'sale_generated' ">
                            <label for="generate_sale">Generate Sale</label>
                        </div>
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

    export default {
        name: 'order-form-item',
        extends: BaseDataItem,
        data() {
            return {
                statuses: {},
                curItem: {}
            }
        },
        methods: {
            save({ item, data }) {
                return apiOrders.saveOrder({ item, data }).then(response => response.data)
            },
            submit() {
                let self = this
                let form = _.pick(this.curItem, ['id', 'uuid', 'noteAdmin', 'noteDealer', 'statusId'])
                form.id = form.uuid // TODO: change to ID back (and change api)?

                if (this.curItem.generateSale) {
                    form.generateSale = this.curItem.generateSale
                }

                this.run({text: 'Saving..', type: 'form'})
                return this.save({ item: form })
                    .then(data => {
                        self.$emit('data-process-update', {
                            running: false,
                            success: data
                        })
                        self.$emit('item-saved')
                    })
                    .catch(response => {
                        self.$emit('data-failed', response)
                    })
            },
            initData() {
                if (this.item.id) {
                    apiOrders.get({
                        id: this.item.id
                    })
                        .then(response => {
                            return this.initDependencies().then(() => {
                                return response
                            })
                        })
                        .then(response => {
                            let item = response.data
                            this.curItem = _.cloneDeep(item)
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
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss">
</style>