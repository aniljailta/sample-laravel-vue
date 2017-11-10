<template>

    <div>
        <data-process v-bind:process="dataProcess" v-bind:with_loader="true"></data-process>
        <form v-if="dataIsReady">
            <div class="form-group">
                <div class="row col-xs-12" v-if="curItem.id">
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
                    <div class="col-xs-12 col-md-3">
                        <label for="date_created" class="control-label">Date Created</label>
                        <div id="date_created">
                            {{ filters.moment(curItem.createdAt, 'YYYY-MM-DD HH:mm:ss', 'MM/DD/YYYY HH:mm:ss') }}
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3">
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
                        <label for="category_id" class="control-label">Category</label>
                        <div class="form-inline">
                            <select id="category_id"
                                    name="category_id"
                                    class="form-control"
                                    initial="off"
                                    v-model="curItem.categoryId">
                                <option :value="null"></option>
                                <option v-for="category in buildingPackageCategories"
                                        v-bind:value="category.id">{{ category.name }}</option>
                            </select>
                            <button type="button"
                                    class="btn btn-primary btn"
                                    title="Add category"
                                    v-on:click.prevent="showCreateCategoryModal">
                                <i class="fa fa-plus" aria-hidden="true"></i>
                            </button>
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
                    <div class="col-xs-12 col-md-5">
                        <label for="building_model_id" class="control-label">Building Model</label>
                        <select id="building_model_id"
                                name="building_model_id"
                                class="form-control"
                                v-model="curItem.buildingModelId"
                                v-on:change="selectBuildingModel($event.target.value)"
                                initial="off">
                            <option v-bind:value="null" selected="selected"></option>
                            <option v-for="buildingModel in buildingModels"
                                    v-bind:value="buildingModel.id"
                                    v-bind:selected="curItem.buildingModelId && curItem.buildingModelId == buildingModel.id">
                                {{ buildingModel.name }} ({{ filters.money(buildingModel.shellPrice) }})
                            </option>
                        </select>
                    </div>
                    <div class="col-xs-12 col-md-7">
                        <label for="total_price" class="control-label">Total Price</label>
                        <div>
                            <span id="total_price" class="label label-success" style="font-size: 100%">
                                {{ filters.money(totalPrice) }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="row col-xs-12 col-sm-12">
                    <div class="col-xs-12 col-md-5" style="margin-bottom: 0.5em">
                        <label class="control-label">Available Options</label>
                        <option-picker v-if="curItem.buildingModel"
                                       v-bind:building-model="curItem.buildingModel"
                                       v-bind:building-options="curItem.options"
                                       v-bind:options="options"
                                       v-bind:option-categories="optionCategories">
                        </option-picker>
                        <div v-else>Select building model to view options list.</div>
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <label class="control-label">Selected Options</label>
                        <option-manager ref="optionManager"
                                        v-bind:total.sync="totalOptions"
                                        v-bind:options="options"
                                        v-bind:building-options="curItem.options"
                                        v-bind:building-model="curItem.buildingModel"
                                        v-bind:editable="false">
                        </option-manager>
                    </div>
                </div>
                <div class="row col-xs-12 col-sm-12">
                    <div class="col-xs-12">
                        <label class="control-label">Files</label>
                        <file-manager ref="fileManager"
                                      v-bind:options="fileManager"
                                      v-bind:storable_type="'building-package'"
                                      v-bind:storable_id="curItem.id ? curItem.id : null"
                                      v-bind:files="curItem.files">
                        </file-manager>
                    </div>
                </div>
            </div>
        </form>

        <category-modal-create v-if="createCategoryModal" :show="true"
                               v-on:close="closeCreateCategoryModal"
                               v-on:saved="initDependencies('package-categories')"
                               v-bind:item="{}">
        </category-modal-create>
    </div>

</template>

<script type="text/babel">
    import BaseDataItem from 'src/components/views/_base/Block/DataItem.vue'
    import FileManager from 'src/components/views/partials/FileManager.vue'
    import OptionPicker from './Optionpicker.vue'
    import OptionManager from './OptionManager.vue'

    import manageOptionPickerDataMixin from 'src/components/ui/Optionpicker/manage-data-mixin.js'
    import apisMixin from './apis-mixin'
    import apiBuildingPackages from 'src/api/building-packages'

    import objectToFormData from 'src/helpers/object-to-form-data'
    import convertKeys from 'convert-keys'

    export default {
        name: 'building-package-form',
        extends: BaseDataItem,
        mixins: [manageOptionPickerDataMixin, apisMixin],
        data() {
            return {
                createCategoryModal: false,
                // required models
                buildingPackageCategories: [],
                activeFlags: {},
                buildingModels: [],
                options: [],
                optionCategories: [],
                curItem: {
                    shellPrice: 0, // should be reactive
                    options: []
                },
                totalOptions: 0,
                alias: {
                    'buildingOptions': 'curItem.options'
                },
                fileManager: {
                    browseOnZoneClick: false,
                    dropZoneEnabled: true,
                    showPreview: true,
                    showBrowse: true,
                    showCaption: true,
                    uploadAsync: false,
                    uploadUrl: '/api/files/',
                    deleteUrl: '/api/files/'
                }
            }
        },
        components: {
            FileManager,
            OptionPicker,
            OptionManager,
            CategoryModalCreate: function(resolve) {
                require(['src/components/views/building-package-categories/modals/ModalCreate.vue'], resolve)
            }
        },
        computed: {
            totalPrice() {
                if (_.isUndefined(this.curItem.buildingModel)) return 0
                return parseFloat(this.totalOptions) + parseFloat(this.curItem.buildingModel.shellPrice)
            }
        },
        methods: {
            save({ item, data }) {
                return apiBuildingPackages.save({ item, data }).then(response => response.data)
            },
            submit() {
                let item = _.merge({}, {
                    name: this.curItem.name,
                    description: this.curItem.description,
                    buildingModelId: this.curItem.buildingModelId,
                    categoryId: this.curItem.categoryId
                }, {
                    packageOptions: this.$refs.optionManager.getOptions(),
                    uploadFiles: this.$refs.fileManager.$refs.uploadInput[0].files })

                if (this.curItem.id) item.id = this.curItem.id
                if (this.curItem.isActive) item.isActive = this.curItem.isActive

                let form = objectToFormData(convertKeys.toSnake(item))
                this.run({text: 'Saving..', type: 'form'})
                return this.save({item: item, data: form})
                    .then(message => {
                        this.$emit('data-process-update', {
                            running: false,
                            success: message
                        })
                        this.$emit('item-saved')
                    })
                    .catch(response => {
                        this.$emit('data-failed', response)
                    })
            },
            selectBuildingModel(modelId) {
                let buildingModel = _.find(this.buildingModels, { id: parseInt(modelId) })
                this.$set(this.curItem, 'buildingModel', buildingModel)
                // do we need to reset options?
                this.$set(this.curItem, 'options', [])
            },
            showCreateCategoryModal() {
                this.createCategoryModal = true
            },
            closeCreateCategoryModal() {
                this.createCategoryModal = false
            },
            initData() {
                if (this.item.id) {
                    apiBuildingPackages.get({
                        id: this.item.id,
                        query: {
                            include: [
                                'building_model.style',
                                'category.files',
                                'options.option.allowable_models',
                                'files'
                            ]
                        }
                    })
                        .then(response => {
                            return this.initDependencies().then(() => response)
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
            initDependencies(dep = null) {
                const datas = []

                if (dep === null) {
                    datas.push(this.receiveActiveFlags())
                    datas.push(this.receiveOptionCategories())
                    datas.push(this.receiveOptions())
                    datas.push(this.receiveBuildingModels())
                    datas.push(this.receiveBuildingPackageCategories())
                }

                if (dep === 'package-categories') {
                    datas.push(this.receiveBuildingPackageCategories())
                }

                return Promise.all(datas)
                    .then(response => {
                        if (dep === null) {
                            this.activeFlags = response[0].data
                            this.optionCategories = response[1].data
                            this.options = response[2].data.data
                            this.buildingModels = response[3].data.data
                            this.buildingPackageCategories = response[4].data.data
                        }

                        if (dep === 'package-categories') {
                            this.buildingPackageCategories = response[0].data.data
                        }
                        return response
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