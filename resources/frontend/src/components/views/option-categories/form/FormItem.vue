<template>

    <div>
        <data-process v-bind:process="dataProcess" v-bind:with_loader="true"></data-process>
        <form v-if="dataIsReady">
            <div class="form-group">
                <div class="row col-xs-12">
                    <div class="col-xs-12 col-md-3" v-if="curItem.id">
                        <label for="id" class="control-label">ID</label>
                        <div id="id">
                            #{{ curItem.id }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3" v-if="curItem.id">
                        <label for="date_created" class="control-label">Date Created</label>
                        <div id="date_created">
                            {{ filters.moment(curItem.createdAt, 'YYYY-MM-DD HH:mm:ss', 'MM/DD/YYYY HH:mm:ss') }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3" v-if="curItem.id">
                        <label for="date_updated" class="control-label">Date Updated</label>
                        <div id="date_updated">
                            {{ filters.moment(curItem.updatedAt, 'YYYY-MM-DD HH:mm:ss', 'MM/DD/YYYY HH:mm:ss') }}
                        </div>
                    </div>
                </div>

                <!-- common -->
                <div class="row col-xs-12 col-sm-12">
                    <div class="col-xs-4 col-md-3">
                        <label for="name" class="control-label">Name</label>
                        <input id="name" type="text" class="form-control" v-model="curItem.name">
                    </div>
                    <div class="col-xs-4 col-md-3">
                        <label for="group" class="control-label">Group</label>
                        <input id="group" type="text" class="form-control" v-model="curItem.group">
                    </div>
                    <div class="col-xs-4 col-md-2">
                        <label for="qty_limit" class="control-label">Qty Limit</label>
                        <input type="number"
                               id="qty_limit"
                               class="form-control"
                               initial="off"
                               v-model="curItem.qtyLimit"/>
                    </div>
                    <div class="col-xs-4 col-md-3">
                        <label class="control-label">Is Required</label>
                        <div style="clear: both">
                            <div class="btn-group btn-group-sm">
                                <label v-bind:class="[curItem.isRequired ? 'active btn-danger' : '']" class="btn btn-default">
                                    <input type="radio"
                                           name="isRequired"
                                           autocomplete="off"
                                           initial="off"
                                           class="hide"
                                           v-bind:value="1"
                                           v-model="curItem.isRequired"
                                           v-bind:checked="curItem.isRequired">Yes
                                </label>
                                <label v-bind:class="[curItem.isRequired ? '' : 'active']" class="btn btn-default">
                                    <input type="radio"
                                           name="isRequired"
                                           autocomplete="off"
                                           initial="off"
                                           class="hide"
                                           v-bind:value="0"
                                           v-model="curItem.isRequired"
                                           v-bind:checked="!curItem.isRequired">No
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row col-xs-12 col-sm-12">
                    <div class="col-xs-4 col-md-3">
                        <label for="sort_id" class="control-label">Sort ID</label>
                        <input id="sort_id" type="text" class="form-control" v-model="curItem.sortId">
                    </div>
                </div>
            </div>
        </form>
    </div>

</template>

<script type="text/babel">
    import BaseDataItem from 'src/components/views/_base/Block/DataItem.vue'
    import apiOptionCategories from 'src/api/option-categories'
    import objectToFormData from 'src/helpers/object-to-form-data'
    import convertKeys from 'convert-keys'

    export default {
        name: 'option-category-form',
        extends: BaseDataItem,
        data() {
            return {
                curItem: {
                    name: null,
                    group: null,
                    qtyLimit: null,
                    isRequired: 0
                }
            }
        },
        components: {},
        methods: {
            save({ item, data }) {
                return apiOptionCategories.save({ item, data }).then(response => response.data)
            },
            submit() {
                let self = this
                let item = {}

                if (this.curItem.id) item.id = this.curItem.id
                item.name = this.curItem.name
                item.group = this.curItem.group
                item.isRequired = this.curItem.isRequired
                item.qtyLimit = this.curItem.qtyLimit
                item.sortId = this.curItem.sortId

                let form = objectToFormData(convertKeys.toSnake(item))
                this.run({text: 'Saving..', type: 'form'})
                return this.save({ item: item, data: form })
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
                    apiOptionCategories.get({
                        id: this.item.id,
                        query: {}
                    })
                        .then(response => {
                            return this.initDependencies().then(() => response)
                        })
                        .then(response => {
                            let item = response.data
                            this.curItem = _.cloneDeep(item)
                            this.$set(this.curItem, 'isRequired', this.curItem.isRequired === true ? 1 : 0)
                        })
                        .then(() => {
                            this.$emit('data-ready')
                        })
                        .catch(response => {
                            this.$emit('data-failed', response)
                        })
                } else {
                    this.initDependencies().then(() => {
                        this.$emit('data-ready')
                    })
                }
            },
            initDependencies() {
                return new Promise((resolve) => { resolve() })
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>
    .checkbox {
        label {
            font-weight: bold;
        }
    }

    .row {
        margin-bottom: 0.5em;
    }
</style>