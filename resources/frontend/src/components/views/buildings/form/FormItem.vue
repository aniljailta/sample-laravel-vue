<template>

    <div>
        <data-process v-bind:process="dataProcess" v-bind:with_loader="true"></data-process>
        <form v-if="dataIsReady">
            <div class="form-group">
                <div class="row col-xs-12">
                    <div v-show="this.$parent.$parent.$parent.modal.mode === 'create'">
                        <div class="col-md-12 checkbox">
                            <div class="col-xs-12 col-md-3">
                                <input type="checkbox" id="is-used" value="" v-model="isUsed">
                                <label for="is-used">Building is Used</label>
                            </div>
                        </div>
                        <div v-if="isUsed" class="row col-xs-12 col-sm-12">
                            <div class="col-xs-12 col-md-3">
                                <label for="rto_company_id" class="control-label">RTO Contract Owner</label>
                                <select id="rto_company_id"
                                        name="rto_company_id"
                                        class="form-control"
                                        v-model="curItem.buildingRtoContractOwnerId"
                                        initial="off">
                                    <option v-bind:value="null" selected="selected"></option>
                                    <option v-for="rtoCompany in rtoCompanies"
                                            v-bind:value="rtoCompany.id"
                                            v-bind:selected="curItem.buildingRtoContractOwnerId && curItem.buildingRtoContractOwnerId == rtoCompany.id">
                                        {{ rtoCompany.name }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-xs-12 col-md-3">
                                <label for="date_building_returned" class="control-label">Date Building Returned</label>
                                <datepicker id="date_building_returned"
                                            v-bind:width="'100%'"
                                            v-bind:value="filters.moment(curItem.dateBuildingReturned, 'YYYY-MM-DD HH:mm:ss', 'MM/DD/YYYY')"
                                            v-bind:format="'MM/dd/yyyy'"
                                            v-bind:placeholder="'MM/DD/YYYY'"
                                            ref="dateBuildingReturned"></datepicker>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3">
                        <label for="serial_number" class="control-label">
                            Serial Number
                        </label>
                        <div id="serial_number">
                            <serial-number ref="serialNumber"
                                           :id="curItem.id || null"
                                           :serial-number="curItem.serialNumber || null"
                                           :building-model="curItem.buildingModel || null">
                            </serial-number>
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

                <div class="row col-xs-12">
                    <div class="col-xs-12 col-md-4">
                        <label for="building_packages" class="control-label">Building Packages</label>
                        <select id="building_packages"
                                name="building_packages"
                                class="form-control"
                                v-model="curItem.buildingPackageId"
                                v-on:change="selectBuildingPackage($event.target.value)"
                                initial="off">
                            <option v-bind:value="null" selected="selected"></option>
                            <option v-for="buildingPackage in buildingPackages"
                                    v-bind:value="buildingPackage.id"
                                    v-bind:selected="curItem.buildingPackageId && curItem.buildingPackageId == buildingPackage.id">
                                {{ buildingPackage.name }}
                            </option>
                        </select>
                    </div>
                </div>

                <!-- common -->
                <div class="row col-xs-12 col-sm-12">
                    <div class="col-xs-12 col-md-3">
                        <label for="plant_id" class="control-label">Manufacturing Plant</label>
                        <select id="plant_id"
                                name="plant_id"
                                class="form-control"
                                v-model="curItem.plantId"
                                initial="off">
                            <option v-bind:value="null" selected="selected"></option>
                            <option v-for="plant in plants"
                                    v-bind:value="plant.id"
                                    v-bind:selected="curItem.plantId && curItem.plantId == plant.id">
                                {{ plant.name }}
                            </option>
                        </select>
                    </div>
                    <div class="col-xs-12 col-md-3">
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
                    <div class="col-xs-12 col-md-3">
                        <label for="shell_price" class="control-label">Shell Price</label>
                        <div class="btn-group">
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input type="number"
                                           id="shell_price"
                                           class="form-control"
                                           initial="off"
                                           v-model="curItem.shellPrice"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-3">
                        <label for="total_price" class="control-label">Total Price</label>
                        <div>
                            <span id="total_price" class="label label-success" style="font-size: 100%">{{ filters.money(totalPrice) }}</span>
                        </div>
                    </div>
                </div>
                <div class="row col-xs-12 col-sm-12">
                    <div class="col-xs-4 col-md-3">
                        <label for="width" class="control-label">Width</label>
                        <input id="width" type="text" class="form-control" v-model="curItem.width" disabled>
                    </div>
                    <div class="col-xs-4 col-md-3">
                        <label for="length" class="control-label">Length</label>
                        <input id="length" type="text" class="form-control" v-model="curItem.length" disabled>
                    </div>
                    <div class="col-xs-4 col-md-3">
                        <label for="height" class="control-label">Height</label>
                        <input id="height" type="text" class="form-control" v-model="curItem.height" disabled>
                    </div>
                </div>
                <div class="row col-xs-12 col-sm-12">
                    <div class="col-xs-12 col-md-5" style="margin-bottom: 0.5em">
                        <label class="control-label">Available Options</label>
                        <option-picker v-if="curItem.buildingModel"
                                       v-bind:building-model="curItem.buildingModel"
                                       v-bind:building-options="curItem.buildingOptions"
                                       v-bind:options="options"
                                       v-bind:option-categories="optionCategories">
                        </option-picker>
                        <div v-else>Select building model to view options list.</div>
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <label class="control-label">Selected Options</label>
                        <option-manager
                                        v-on:option-manager-update-option="optionsUpdated"
                                        v-on:option-manager-add-option="optionsUpdated"
                                        v-on:option-manager-check-building-package="optionsUpdated"
                                        v-on:option-manager-decrease-children-options="optionsUpdated"
                                        v-on:option-manager-decrease-option="optionsUpdated"
                                        v-on:option-manager-increase-option="optionsUpdated"
                                        ref="optionManager"
                                        v-bind:total.sync="totalOptions"
                                        v-bind:options="options"
                                        v-bind:building-options="curItem.buildingOptions"
                                        v-bind:building-model="curItem.buildingModel"
                                        v-bind:editable="true">
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
                                      v-bind:upload_async="curItem.id ? true : false"
                                      v-bind:files="curItem.files">
                        </file-manager>
                    </div>
                </div>
                <div class="row col-xs-12 col-sm-12">
                    <div class="col-xs-12 col-md-12">
                        <label for="Notes" class="control-label">Notes</label>
                        <textarea id="Notes" type="text" class="form-control" v-model="curItem.notes"></textarea>
                    </div>
                </div>
            </div>
        </form>
    </div>

</template>

<script type="text/babel">
    import BaseDataItem from 'src/components/views/_base/Block/DataItem.vue'
    import Datepicker from 'src/components/ui/Datepicker.vue'
    import FileManager from 'src/components/views/partials/FileManager.vue'
    import OptionPicker from './Optionpicker.vue'
    import OptionManager from './OptionManager.vue'
    import SerialNumber from '../partials/SerialNumber.vue'
    import manageOptionPickerDataMixin from 'src/components/ui/Optionpicker/manage-data-mixin.js'

    import apiPlants from 'src/api/plants'
    import apiBuildings from 'src/api/buildings'
    import apiOptions from 'src/api/options'
    import apiBuildingModels from 'src/api/building-models'
    import apiBuildingPackages from 'src/api/building-packages'
    import apiRtoCompanies from 'src/api/rto-companies'

    import objectToFormData from 'src/helpers/object-to-form-data'
    import convertKeys from 'convert-keys'

    export default {
        name: 'building-form',
        extends: BaseDataItem,
        mixins: [manageOptionPickerDataMixin],
        data() {
            return {
                // data dependency
                plants: {},
                buildingModels: {},
                buildingPackages: {},
                options: {},
                optionCategories: {},
                rtoCompanies: {},
                defaultOptions: [],
                curItem: {
                    shellPrice: 0, // should be reactive
                    buildingOptions: [],
                    buildingModelId: null,
                    buildingRtoContractOwnerId: null
                },
                isSelectingPackage: false,
                totalOptions: 0,
                // custom els
                alias: {
                    'buildingOptions': 'curItem.buildingOptions'
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
                },
                isUsed: false
            }
        },
        components: {
            FileManager,
            Datepicker,
            OptionPicker,
            OptionManager,
            SerialNumber
        },
        computed: {
            totalPrice() {
                return parseFloat(this.totalOptions) + parseFloat(this.curItem.shellPrice)
            },
            id() {
                if (!_.isUndefined(this.item.id)) {
                    return this.item.id
                }
                return null
            },
            buildingModelId() {
                return this.curItem.buildingModelId
            }
        },
        watch: {
            buildingModelId() {
                if (!this.isSelectingPackage) {
                    this.curItem.buildingPackageId = null
                } else {
                    this.isSelectingPackage = false
                }
            }
        },
        methods: {
            save({ item, data }) {
                return apiBuildings.save({ item, data }).then(response => response.data)
            },
            submit() {
                let self = this
                let item = _.merge({}, {
                    buildingModelId: this.curItem.buildingModelId,
                    plantId: this.curItem.plantId,
                    width: this.curItem.width,
                    height: this.curItem.height,
                    length: this.curItem.length,
                    notes: this.curItem.notes || null,
                    shellPrice: this.curItem.shellPrice
                }, {
                    buildingOptions: this.$refs.optionManager.getOptions(),
                    upload_files: this.$refs.fileManager.$refs.uploadInput[0].files
                })

                if (this.curItem.id) item.id = this.curItem.id

                if (this.curItem.buildingRtoContractOwnerId) {
                    item.buildingRtoContractOwnerId = this.curItem.buildingRtoContractOwnerId
                }
                if (this.$refs.dateBuildingReturned && this.$refs.dateBuildingReturned.val) {
                    item.dateBuildingReturned = this.filters.moment(this.$refs.dateBuildingReturned.val, 'MM/DD/YYYY', 'YYYY-MM-DD')
                }

                let form = objectToFormData(convertKeys.toSnake(item))

                this.run({text: 'Saving..', type: 'form'})
                return this.save({item: item, data: form})
                    .then(data => {
                        self.$emit('data-process-update', {
                            running: false,
                            success: data.msg
                        })
                        self.$emit('item-saved')
                    })
                    .catch(response => {
                        self.$emit('data-failed', response)
                    })
            },
            selectBuildingModel(modelId) {
                let buildingModel = _.find(this.buildingModels, { id: parseInt(modelId) })
                this.curItem.buildingModel = buildingModel
                if (_.isUndefined(buildingModel)) {
                    this.curItem.shellPrice = null
                    this.curItem.width = null
                    this.curItem.length = null
                    this.curItem.height = null
                } else {
                    this.curItem.shellPrice = buildingModel.shellPrice
                    this.curItem.width = buildingModel.width
                    this.curItem.length = buildingModel.length
                    this.curItem.height = buildingModel.wallHeight
                }

                // get available default options
                let availableOptions = _.filter(this.options, function (item) {
                    return _.includes(_.map(item.allowableModels, 'id'), parseInt(modelId))
                }, this)
                this.defaultOptions = []
                if (!_.isUndefined(buildingModel)) {
                    for (let item of availableOptions) {
                        if (item.isDefault === 1) {
                            let defaultColor = _.find(item.allowableColors, {id: _.parseInt(item.defaultColorId)})
                            let quantity = 1
                            switch (item.forceQuantity) {
                                case 'building_length':
                                    quantity = _.parseInt(buildingModel.length)
                                    break
                                case 'wall_area':
                                    quantity = (_.parseInt(buildingModel.length) + _.parseInt(buildingModel.width)) * _.parseInt(buildingModel.wallHeight)
                                    break
                                case 'floor_area':
                                    quantity = _.parseInt(buildingModel.length) * _.parseInt(buildingModel.width)
                                    break
                            }

                            this.defaultOptions.push({
                                option: item,
                                optionId: item.id,
                                quantity: quantity,
                                unitPrice: item.unitPrice,
                                color: defaultColor,
                                minQuantity: quantity
                            })
                        }
                    }
                }

                // set default options
                this.curItem.buildingOptions = this.defaultOptions
            },
            selectBuildingPackage(buildingPackageId) {
                if (buildingPackageId !== '') {
                    this.run({text: 'Applying building package..', type: 'form'})
                    apiBuildingPackages.get({
                        id: buildingPackageId,
                        query: {
                            include: [
                                'building_model.style',
                                'category.files',
                                'options.option.allowable_models',
                                'options.option.allowable_colors',
                                'options.option.allowable_colors.image',
                                'files'
                            ],
                            per_page: 99999
                        }
                    })
                    .then(response => {
                        let buildingPackage = response.data
                        this.isSelectingPackage = true
                        this.curItem.buildingModelId = buildingPackage.buildingModel.id
                        this.selectBuildingModel(buildingPackage.buildingModel.id)
                        this.curItem.buildingOptions = buildingPackage.options

                        // set default option if there is not another option indicated
                        for (let item of this.defaultOptions) {
                            if (this.curItem.buildingOptions.filter(function(e) { return e.option.categoryId === item.option.categoryId }).length === 0) {
                                this.curItem.buildingOptions.push(item)
                            }
                        }
                    })
                    .then(() => {
                        this.$emit('data-ready')
                    })
                    .catch(response => {
                        this.$emit('data-failed', response)
                    })
                }
            },
            initData() {
                if (this.id) {
                    apiBuildings.get({
                        id: this.id,
                        query: {
                            include: [
                                'building_model',
                                'building_model.style',
                                'building_options',
                                'building_options.option',
                                'building_options.option.allowable_colors',
                                'building_options.option.allowable_colors.image',
                                'building_options.colors',
                                'building_options.option_color.color.image',
                                'files'
                            ]
                        }
                    })
                        .then(response => {
                            return this.initDependencies().then(() => response)
                        })
                        .then(response => {
                            let item = response.data
                            if (this.$parent.$parent.$parent.modal.mode === 'duplicate') {
                                delete item.id
                                delete item.serialNumber
                                delete item.plantId
                            }
                            this.curItem = _.cloneDeep(item)
                            this.totalOptions = this.curItem.totalOptions
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
                let optionsQuery = {
                    per_page: 99999,
                    where: {
                        is_active: 'yes'
                    },
                    include: {
                        category: true,
                        files: true,
                        allowable_models: {
                            where: {
                                is_active: 'yes'
                            },
                            fields: ['id']
                        },
                        allowable_colors: true,
                        'allowable_colors.image': true
                    }
                }
                _.set(optionsQuery, ['where', 'category.name', 'neq'], 'Order')
                const datas = [
                    apiPlants.get({}),
                    apiOptions.categories({}),
                    apiOptions.get({
                        query: optionsQuery
                    }),
                    apiBuildingModels.get({
                        query: {
                            include: ['style'],
                            where: {
                                isActive: 'yes'
                            },
                            order_by: ['style_id asc', 'width asc', 'length asc'],
                            per_page: 99999
                        }
                    }),
                    apiBuildingPackages.get({
                        query: {
                            include: [
                            ],
                            per_page: 99999
                        }
                    }),
                    apiRtoCompanies.getRtoCompanies()
                ]

                return Promise.all(datas)
                    .then(response => {
                        this.plants = response[0].data.data
                        this.optionCategories = response[1].data
                        this.options = response[2].data.data
                        this.buildingModels = response[3].data.data
                        this.buildingPackages = response[4].data.data
                        this.rtoCompanies = response[5].data.data
                        return response
                    })
            },
            optionsUpdated() {
                if (this.isSelectingPackage) {
                    this.isSelectingPackage = false
                } else {
                    this.curItem.buildingPackageId = null
                }
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

    .datepicker{
        position: relative;
        display: block !important;
        padding: 0;
    }
</style>