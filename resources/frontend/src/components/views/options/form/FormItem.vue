<template>

    <div>
        <data-process v-bind:process="dataProcess" v-bind:with_loader="true"></data-process>
        <form v-if="dataIsReady">
            <div class="form-group">
                <div class="row col-xs-12" v-if="curItem.id">
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
                    <div class="col-xs-12" :class="[showIsDefault ? 'col-md-4' : 'col-md-6']">
                        <label for="name" class="control-label">Name</label>
                        <input id="name" type="text" class="form-control" v-model="curItem.name">
                    </div>
                    <div class="col-xs-12 col-md-4">
                        <label for="category_id" class="control-label">Category</label>
                        <select id="category_id"
                                name="category_id"
                                class="form-control"
                                initial="off"
                                v-model="curItem.categoryId">
                            <option :value="null"></option>
                            <option v-for="category in optionCategories"
                                    v-bind:value="category.id">
                                {{ category.name }} ({{ category.group }})
                            </option>
                        </select>
                    </div>
                    <div class="col-xs-3 col-md-2" v-if="showIsDefault">
                        <label class="control-label">Is Default</label>
                        <div style="clear: both">
                            <div class="btn-group btn-group-sm">
                                <label v-bind:class="[curItem.isDefault ? 'active btn-danger' : '']" class="btn btn-default">
                                    <input type="radio"
                                           name="isDefault"
                                           autocomplete="off"
                                           initial="off"
                                           class="hide"
                                           v-bind:value="1"
                                           v-model="curItem.isDefault"
                                           v-bind:checked="curItem.isDefault">Yes
                                </label>
                                <label v-bind:class="[curItem.isDefault ? '' : 'active']" class="btn btn-default">
                                    <input type="radio"
                                           name="isDefault"
                                           autocomplete="off"
                                           initial="off"
                                           class="hide"
                                           v-bind:value="0"
                                           v-model="curItem.isDefault"
                                           v-bind:checked="!curItem.isDefault">No
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-2">
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
                </div>

                <div class="row col-xs-12 col-sm-12" v-show="showOrderOptions">
                    <div class="col-xs-12 col-md-4">
                        <div class="checkbox">
                            <input id="taxable" type="checkbox" class="form-control" v-model="curItem.taxable" true-value=1>
                            <label for="taxable" class="control-label">Taxable?</label>
                        </div>
                        <div class="checkbox">
                            <input id="rto" type="checkbox" class="form-control" v-model="curItem.rto"  true-value=1>
                            <label for="rto" class="control-label">RTO?</label>
                        </div>
                        <div class="checkbox">
                            <input id="rto-deposit" type="checkbox" class="form-control" v-model="curItem.rtoDeposit">
                            <label for="rto-deposit" class="control-label">Add to RTO Deposit?</label>
                        </div>
                        <div class="checkbox">
                            <input id="delivery-charge" type="checkbox" class="form-control" v-model="curItem.deliveryCharge">
                            <label for="delivery-charge" class="control-label">Is this a delivery charge?</label>
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
                    <div class="col-xs-6 col-sm-4">
                        <label class="control-label">Unit Price</label>
                        <div class="btn-group">
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon">$</span>
                                    <input type="number"
                                           class="form-control"
                                           initial="off"
                                           v-model="curItem.unitPrice"/>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-4">
                        <label for="allowable_models_id" class="control-label">Allowable Models</label>
                        <div>
                            <select class="form-control"
                                    id="allowable_models_id"
                                    multiple="1"
                                    name="allowable_models_id[]"
                                    v-model="curItem.allowableModelsId"
                                    ref="allowableModels">

                                <optgroup v-bind:label="allowableStyle.name" v-for="allowableStyle in allowableStyles">
                                    <option v-for="buildingModel in allowableStyle.buildingModels"
                                            v-bind:value="buildingModel.id">
                                        {{ buildingModel.name }}
                                    </option>
                                </optgroup>

                            </select>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-4" v-if="showAllowableColors">
                        <label for="allowable_colors_id" class="control-label">Allowable Colors</label>
                        <div>
                            <select class="form-control"
                                    id="allowable_colors_id"
                                    multiple="1"
                                    name="allowable_colors_id[]"
                                    v-model="curItem.allowableColorsId"
                                    ref="allowableColors">
                                <option v-for="allowableColor in allowableColors"
                                        v-bind:value="allowableColor.id">
                                    {{ allowableColor.name }}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row col-xs-12">
                    <div class="col-xs-12 col-md-4">
                        <label for="constraint_type" class="control-label">Constraint Type</label>
                        <select id="constraint_type"
                                name="constraint_type"
                                class="form-control"
                                v-model="curItem.constraintType"
                                initial="off">
                            <option :value="null"></option>
                            <option v-for="constraintType in constraintTypes"
                                    v-bind:value="constraintType.id"
                                    v-bind:selected="curItem.constraintType && curItem.constraintType == constraintType.id">
                                {{ constraintType.name }}
                            </option>
                        </select>
                    </div>
                    <div class="col-xs-12 col-md-4" v-if="curItem.constraintType">
                        <label for="force_quantity" class="control-label">Quantity Constraint</label>
                        <select id="force_quantity"
                                name="force_quantity"
                                class="form-control"
                                v-model="curItem.forceQuantity"
                                initial="off">
                            <option :value="null"></option>
                            <option v-for="forceQuantityFlag in forceQuantityFlags"
                                    v-bind:value="forceQuantityFlag.id"
                                    v-bind:selected="curItem.forceQuantity && curItem.forceQuantity == forceQuantityFlag.id">
                                {{ forceQuantityFlag.name }}
                            </option>
                        </select>
                    </div>
                    <div class="col-xs-12 col-md-4" v-if="showAllowableColors">
                        <label class="control-label">Default Color</label>
                        <colorpicker id="default_color"
                                name="default_color"
                                ref="defaultColor"
                                v-on:select-color="selectDefaultColor"
                                v-bind:colors="defaultColorOptions"
                                v-bind:color="defaultColor"
                                initial="off">
                        </colorpicker>
                    </div>
                </div>
                <div class="row col-xs-12 col-sm-12">
                    <div class="alert alert-warning" v-if="curItem.constraintType && curItem.forceQuantity">
                        <p>
                            Option quantity must be {{ selectedConstraintType.name }} {{ selectedForceQuantity.name }}
                        </p>
                    </div>
                </div>

                <div class="row col-xs-12 col-sm-12">
                    <div class="col-xs-12">
                        <label class="control-label">Files</label>
                        <file-manager ref="fileManager"
                                      v-bind:options="fileManager"
                                      v-bind:storable_type="'option'"
                                      v-bind:storable_id="curItem.id ? curItem.id : null"
                                      v-bind:files="curItem.files">
                        </file-manager>
                    </div>
                </div>
            </div>
        </form>
    </div>

</template>

<script type="text/babel">
    import BaseDataItem from 'src/components/views/_base/Block/DataItem.vue'
    import FileManager from 'src/components/views/partials/FileManager.vue'
    import Colorpicker from './Color.vue'

    import apiOptions from 'src/api/options'
    import apiStyles from 'src/api/styles'
    import apiColors from 'src/api/colors'

    import objectToFormData from 'src/helpers/object-to-form-data'
    import convertKeys from 'convert-keys'

    import 'bootstrap-multiselect'
    import 'bootstrap-multiselect/dist/js/bootstrap-multiselect-collapsible-groups.js'
    import '!style!css!less!bootstrap-multiselect/dist/css/bootstrap-multiselect.css'

    export default {
        name: 'option-form-item',
        extends: BaseDataItem,
        data() {
            return {
                allowableStyles: {},
                allowableColors: {},
                optionCategories: {},
                activeFlags: {},
                forceQuantityFlags: {},
                defaultColor: null,
                defaultColorOptions: {},
                constraintTypes: {},
                selectedConstraintType: null,
                selectedForceQuantity: null,
                curItem: {
                    id: null,
                    name: null,
                    description: null,
                    allowableModelsId: [],
                    allowableColorsId: [],
                    categoryId: null,
                    forceQuantity: null,
                    unitPrice: 0,
                    isActive: 'yes',
                    isDefault: 0,
                    defaultColor: null,
                    taxable: null,
                    rtoDeposit: null,
                    deliveryCharge: null,
                    constraintType: null
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
            Colorpicker
        },
        computed: {
            id() {
                if (!_.isUndefined(this.item.id)) {
                    return this.item.id
                }
                return null
            },
            showAllowableColors() {
                if (this.curItem.categoryId) {
                    let categoryId = _.parseInt(this.curItem.categoryId)
                    let selectedCategory = _.find(this.optionCategories, function (category) {
                        return _.parseInt(category.id) === categoryId
                    })
                    if (selectedCategory.group === 'roof' || selectedCategory.group === 'siding' || selectedCategory.group === 'trim') {
                        this.$nextTick(() => {
                            this.initMultiSelect(this.$refs.allowableColors, 'allowableColorsId')
                        })
                        return true
                    }
                }
                this.curItem.defaultColorId = null
                return false
            },
            showOrderOptions() {
                if (this.curItem.categoryId) {
                    let categoryId = _.parseInt(this.curItem.categoryId)
                    let selectedCategory = _.find(this.optionCategories, category => _.parseInt(category.id) === categoryId)
                    if (selectedCategory.group === 'order') {
                        return true
                    }
                }

                return false
            },
            showIsDefault() {
                if (this.curItem.categoryId) {
                    let categoryId = _.parseInt(this.curItem.categoryId)
                    let selectedCategory = _.find(this.optionCategories, category => _.parseInt(category.id) === categoryId)
                    if (selectedCategory.group === 'roof' || selectedCategory.group === 'siding' || selectedCategory.group === 'trim') {
                        return true
                    }
                }
                this.curItem.isDefault = 0
                return false
            }
        },
        created() {
            this.$watch('curItem.forceQuantity', () => {
                let self = this
                this.selectedForceQuantity = _.find(this.forceQuantityFlags, function(forceQuantity) { return forceQuantity.id === self.curItem.forceQuantity })
            })
            this.$watch('curItem.constraintType', () => {
                let self = this
                this.selectedConstraintType = _.find(this.constraintTypes, function(constraintType) { return constraintType.id === self.curItem.constraintType })
            })
        },
        methods: {
            save({ item, data }) {
                return apiOptions.save({ item, data }).then(response => response.data)
            },
            submit() {
                let self = this
                let item = _.merge({}, {
                    name: this.curItem.name,
                    description: this.curItem.description,
                    allowableModelsId: this.curItem.allowableModelsId,
                    allowableColorsId: this.curItem.allowableColorsId,
                    categoryId: this.curItem.categoryId,
                    forceQuantity: this.curItem.forceQuantity,
                    unitPrice: this.curItem.unitPrice,
                    isActive: this.curItem.isActive,
                    isDefault: this.curItem.isDefault,
                    defaultColorId: this.curItem.defaultColorId,
                    constraintType: this.curItem.constraintType,
                    rtoDeposit: this.curItem.rtoDeposit,
                    deliveryCharge: this.curItem.deliveryCharge
                }, {
                    upload_files: this.$refs.fileManager.$refs.uploadInput[0].files
                })

                if (this.curItem.id) item.id = this.curItem.id
                if (this.curItem.taxable) item.taxable = this.curItem.taxable
                if (this.curItem.rto) item.rto = this.curItem.rto

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
            isAllowableModel(model) {
                let isAllowable = _.find(this.curItem.allowableModels, function(item) {
                    return item.id === model.id
                })

                return !_.isUndefined(isAllowable)
            },
            initMultiSelect($el, name) {
                let self = this
                $($el).multiselect({
                    buttonWidth: '100%',
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
                        self.setDefaultColorOptions()
                    }
                })
            },
            initData() {
                if (this.id) {
                    apiOptions.get({
                        id: this.id,
                        query: {
                            include: [
                                'files',
                                'allowable_models',
                                'allowable_colors',
                                'allowable_colors.image',
                                'category'
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
                            this.curItem.allowableModelsId = _.map(this.curItem.allowableModels, 'id')
                            this.curItem.allowableColorsId = _.map(this.curItem.allowableColors, 'id')
                            this.defaultColor = _.find(this.curItem.allowableColors, function(color) { return _.parseInt(color.id) === _.parseInt(item.defaultColorId) })
                            this.setDefaultColorOptions()
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
                    apiOptions.categories({}),
                    apiOptions.getActiveFlags({}),
                    apiOptions.getForceQuantityFlags({}),
                    apiStyles.get({
                        query: {
                            per_page: 99999,
                            where: {
                                isActive: 'yes'
                            },
                            include: {
                                buildingModels: {
                                    where: {
                                        isActive: 'yes'
                                    }
                                }
                            }
                        }
                    }),
                    apiColors.get({
                        query: {
                            where: {
                                isActive: 'yes'
                            },
                            include: {
                                image: true
                            },
                            per_page: 99999
                        }
                    }),
                    apiOptions.getConstraintTypes({})
                ]

                return Promise.all(datas)
                    .then(response => {
                        this.optionCategories = response[0].data
                        this.activeFlags = response[1].data
                        this.forceQuantityFlags = response[2].data
                        this.allowableStyles = response[3].data.data
                        this.allowableColors = response[4].data.data
                        this.constraintTypes = response[5].data
                        return response
                    })
            },
            dataAllReady() {
                this.$nextTick(() => {
                    this.initMultiSelect(this.$refs.allowableModels, 'allowableModelsId')
                    this.initMultiSelect(this.$refs.allowableColors, 'allowableColorsId')
                })
            },
            selectDefaultColor (color) {
                this.defaultColor = color
                this.curItem.defaultColorId = color.id
            },
            setDefaultColorOptions() {
                let self = this
                this.defaultColorOptions = _.filter(self.allowableColors, (allowableColor) => {
                    let isAllowed = _.findIndex(self.curItem.allowableColorsId, (colorId) => {
                        return allowableColor.id === _.parseInt(colorId)
                    })
                    if (isAllowed !== -1) {
                        return true
                    } else {
                        if (_.parseInt(allowableColor.id) === _.parseInt(self.curItem.defaultColorId)) {
                            self.defaultColor = null
                            self.curItem.defaultColorId = null
                        }
                    }
                    return false
                })
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>

</style>