<template>

    <div>
        <data-process v-bind:process="dataProcess" v-bind:with_loader="true"></data-process>
        <form v-if="dataIsReady">
            <div class="form-group">
                <div class="row col-xs-12">
                    <div class="col-xs-12 col-md-3">
                        <label for="is_active_flags" class="control-label">Is Active</label>
                        <select id="is_active_flags"
                                name="is_active_flags"
                                class="form-control"
                                v-model="curItem.isActive"
                                initial="off">
                            <option v-for="activeFlag in activeFlags"
                                    v-bind:value="activeFlag.id"
                                    v-bind:selected="curItem.isActive && curItem.isActive == activeFlag.id">
                                {{ activeFlag.name }}
                            </option>
                        </select>
                    </div>
                    <div class="col-xs-12 col-md-3" v-if="item.id">
                        <label for="date_created" class="control-label">Date Created</label>
                        <div id="date_created">
                            {{ filters.moment(curItem.createdAt, 'YYYY-MM-DD HH:mm:ss', 'MM/DD/YYYY HH:mm:ss') }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3" v-if="item.id">
                        <label for="date_updated" class="control-label">Date Updated</label>
                        <div id="date_updated">
                            {{ filters.moment(curItem.updatedAt, 'YYYY-MM-DD HH:mm:ss', 'MM/DD/YYYY HH:mm:ss') }}
                        </div>
                    </div>
                </div>

                <!-- common -->
                <div class="row col-xs-12 col-sm-12">
                    <div class="col-xs-12 col-md-6">
                        <label for="name" class="control-label">Name</label>
                        <input id="name" type="text" class="form-control" v-model="curItem.name">
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <label for="style_id" class="control-label">Style</label>
                        <div class="form-inline">
                            <select id="style_id"
                                    name="style_id"
                                    class="form-control"
                                    initial="off"
                                    v-model="curItem.styleId">
                                <option :value="null"></option>
                                <option v-for="style in styles"
                                        v-bind:value="style.id">{{ style.name }}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row col-xs-12 col-sm-12">
                    <div class="col-xs-12 col-md-12">
                        <label for="description" class="control-label">Description</label>
                        <textarea id="description" type="text" class="form-control" v-model="curItem.description"></textarea>
                    </div>
                </div>

                <div class="row col-xs-12 col-sm-12">
                    <div class="col-xs-12 col-md-4">
                        <label for="width" class="control-label">Width</label>
                        <input id="width" type="number" class="form-control" v-model="curItem.width">
                    </div>
                    <div class="col-xs-12 col-md-4">
                        <label for="length" class="control-label">Length</label>
                        <input id="length" type="number" class="form-control" v-model="curItem.length">
                    </div>
                    <div class="col-xs-12 col-md-4">
                        <label for="wallHeight" class="control-label">Wall Height</label>
                        <input id="wallHeight" type="number" class="form-control" v-model="curItem.wallHeight">
                    </div>
                </div>

                <div class="row col-xs-12 col-sm-12">
                    <div class="col-xs-6 col-sm-4">
                        <label class="control-label">Shell Price</label>
                        <div class="btn-group">
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input type="number"
                                           class="form-control"
                                           initial="off"
                                           v-model="curItem.shellPrice"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row col-xs-12 col-sm-12">
                    <div class="col-xs-12 col-sm-6">
                        <label for="allowable_options_id" class="control-label">Allowable Options</label>
                        <div>
                            <select class="form-control"
                                    id="allowable_options_id"
                                    multiple="1"
                                    name="allowable_options_id[]"
                                    v-model="curItem.allowableOptionsId"
                                    ref="allowableOptions">

                                <optgroup v-bind:label="categoryName" v-for="(optionCategory, categoryName) in optionsPerCategory">
                                    <option v-for="allowableOption in optionCategory"
                                            v-bind:value="allowableOption.id">
                                        {{ allowableOption.name }}
                                    </option>
                                </optgroup>

                            </select>
                        </div>
                    </div>
                </div>

                <div class="row col-xs-12 col-sm-12">

                    <div class="col-xs-12 col-sm-4"
                         style="overflow-y: auto; height: 200px"
                         v-if="typeof statusesPerType.build !== 'undefined' ">
                        <h5>
                            <strong>Build status</strong> - default cost
                        </h5>
                        <div class="row form-group form-group-sm"
                             v-for="(status, statusIndex) in statusesPerType.build">
                            <label class="col-sm-5 control-label">
                                {{ status.name }}
                            </label>
                            <div class="col-sm-7">
                                <input class="form-control col-md-1"
                                       v-bind:name="'model_status_cost[' + status.id + ']'"
                                       type="number"
                                       v-bind:value="curItem.modelStatusCost[status.id]"
                                       v-on:input="updateStatusCost(status.id, $event.target.value)">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

</template>

<script type="text/babel">
    import BaseDataItem from 'src/components/views/_base/Block/DataItem.vue'
    import FileManager from 'src/components/views/partials/FileManager.vue'

    import apiBuildingModels from 'src/api/building-models'
    import apisMixin from './apis-mixin'

    import objectToFormData from 'src/helpers/object-to-form-data'
    import convertKeys from 'convert-keys'

    import 'bootstrap-multiselect'
    import 'bootstrap-multiselect/dist/js/bootstrap-multiselect-collapsible-groups.js'
    import '!style!css!less!bootstrap-multiselect/dist/css/bootstrap-multiselect.css'

    export default {
        name: 'building-model-form',
        extends: BaseDataItem,
        mixins: [apisMixin],
        data() {
            return {
                buildingStatuses: [],
                styles: [],
                options: [],
                activeFlags: {},
                curItem: {
                    modelStatusCost: {},
                    allowableOptionsId: []
                }
            }
        },
        components: {
            FileManager
        },
        computed: {
            optionsPerCategory() {
                return _.groupBy(this.options, option => {
                    if (option.category) return option.category.name
                    return 'Uncategoried'
                })
            },
            statusesPerType() {
                if (!_.size(this.buildingStatuses)) return []
                return _.groupBy(this.buildingStatuses, status => status.type)
            }
        },
        methods: {
            save({ item, data }) {
                return apiBuildingModels.save({ item, data }).then(response => response.data)
            },
            submit() {
                let self = this
                let item = _.merge({}, {
                    name: this.curItem.name || null,
                    description: this.curItem.description || null,
                    styleId: this.curItem.styleId || null,
                    shellPrice: this.curItem.shellPrice || null,
                    width: this.curItem.width || null,
                    length: this.curItem.length || null,
                    wallHeight: this.curItem.wallHeight || null,
                    allowableOptionsId: this.curItem.allowableOptionsId || null,
                    modelStatusCost: this.curItem.modelStatusCost || null,
                    isActive: this.curItem.isActive || null
                })

                if (this.curItem.id) {
                    item.id = this.curItem.id
                }

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
            isAllowableOption(item) {
                let isAllowable = _.find(this.curItem.allowableOptions, function(option) {
                    return option.id === item.id
                })

                return !_.isUndefined(isAllowable)
            },
            modelStatusCost(modelBuildingStatus) {
                let modelStatusCost = {}
                _.each(modelBuildingStatus, function(item) {
                    modelStatusCost[item.statusId] = item.cost
                })
                return modelStatusCost
            },
            updateStatusCost(statusId, value) {
                this.curItem.modelStatusCost[statusId] = value
            },
            initMultiSelect($el, name) {
                let self = this
                $($el).multiselect({
                    maxHeight: 300,
                    buttonClass: 'btn btn-default btn-block',
                    enableClickableOptGroups: true,
                    enableCollapsibleOptGroups: true,
                    disableIfEmpty: true,
                    enableFiltering: true,
                    includeSelectAllOption: true,
                    selectAllValue: 'all',
                    selectAllNumber: true,
                    onChange: function(option, checked, select) {
                        self.curItem[name] = [...$el.options].filter(option => option.selected).map(option => option.value)
                    }
                })
            },
            initData() {
                if (this.item.id) {
                    apiBuildingModels.get({
                        id: this.item.id,
                        query: {
                            include: {
                                style: true,
                                model_building_status: true,
                                allowable_options: {
                                    fields: ['id']
                                }
                            }
                        }
                    })
                        .then(response => {
                            return this.initDependencies().then(() => response)
                        })
                        .then(response => {
                            let item = response.data
                            this.curItem = _.cloneDeep(item)
                            this.$set(this.curItem, 'modelStatusCost', this.modelStatusCost(this.curItem.modelBuildingStatus))
                            this.$set(this.curItem, 'allowableOptionsId', _.map(this.curItem.allowableOptions, 'id'))
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
            initDependencies(dep = null) {
                const datas = []

                if (dep === null) {
                    datas.push(this.receiveActiveFlags())
                    datas.push(this.receiveBuildingStatuses())
                    datas.push(this.receiveStyles())
                    datas.push(this.receiveOptions())
                }

                return Promise.all(datas)
                    .then(response => {
                        if (dep === null) {
                            this.activeFlags = response[0].data
                            this.buildingStatuses = response[1].data.data
                            this.styles = response[2].data.data
                            this.options = response[3].data.data
                        }

                        return response
                    })
            },
            dataAllReady() {
                this.$nextTick(() => {
                    this.initMultiSelect(this.$refs.allowableOptions, 'allowableOptionsId')
                })
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>
    .row {
        margin-bottom: 0.5em;
    }
</style>