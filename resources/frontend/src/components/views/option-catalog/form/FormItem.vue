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
                    <div class="col-xs-12 col-md-4">
                        <label for="name" class="control-label">Name</label>
                        <input id="name" type="text" class="form-control" v-model="curItem.name">
                    </div>
                    <div class="col-xs-12 col-md-4">
                        <label for="category_id" class="control-label">Category</label>
                        <div class="form-inline">
                            <select id="category_id"
                                    name="category_id"
                                    class="form-control"
                                    initial="off"
                                    v-model="curItem.categoryId"
                            >
                                <option :value="null"></option>
                                <option v-for="category in optionCategories"
                                        v-bind:value="category.id">{{ category.name }} ({{ category.group }})</option>
                            </select>
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
                    <div class="col-xs-12 col-md-2">
                        <label for="is_required" class="control-label">Is Required</label>
                        <select id="is_required"
                                name="is_required"
                                class="form-control"
                                v-model="curItem.isRequired"
                                initial="off">
                            <option v-for="activeFlag in requiredActiveFlags"
                                    v-bind:value="activeFlag.id"
                                    v-bind:selected="curItem.isRequired && curItem.isRequired == activeFlag.id">
                                {{ activeFlag.title }}
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
                    <div class="col-sm-6">
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

                    <div class="col-xs-12 col-sm-5">
                        <label for="allowable_companies_id" class="control-label">Allowable Companies</label>
                        <select class="form-control"
                                id="allowable_companies_id"
                                multiple="1"
                                name="allowable_companies_id[]"
                                v-model="curItem.allowableCompaniesId"
                                ref="allowableCompanies">

                                <option v-for="company in companies"
                                        v-bind:value="company.companyId">
                                    {{ company.name }}
                                </option>
                        </select>
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

                    <div class="col-xs-12 col-md-4">
                        <label for="3d_model" class="control-label">3D model</label>
                        <input id="3d_model" placeholder="3D model" type="text" class="form-control" v-model="curItem['3dModel']">
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
                                      v-bind:storable_type="'option-catalog'"
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

    import apiOptionCatalog from 'src/api/option-catalog'

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
                optionCategories: {},
                activeFlags: {},
                forceQuantityFlags: {},
                constraintTypes: {},
                selectedConstraintType: null,
                selectedForceQuantity: null,
                requiredActiveFlags: [
                    { id: 1, title: 'Yes' },
                    { id: 0, title: 'No' }
                ],
                curItem: {
                    id: null,
                    name: null,
                    description: null,
                    categoryId: null,
                    forceQuantity: null,
                    unitPrice: 0,
                    isActive: 'yes',
                    isRequired: 0,
                    taxable: null,
                    rtoDeposit: null,
                    deliveryCharge: null,
                    constraintType: null,
                    '3dModel': null,
                    allowableCompaniesId: []
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
            FileManager
        },
        computed: {
            id() {
                if (!_.isUndefined(this.item.id)) {
                    return this.item.id
                }
                return null
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
                return apiOptionCatalog.save({ item, data }).then(response => response.data)
            },
            submit() {
                let self = this
                let item = _.merge({}, {
                    name: this.curItem.name,
                    description: this.curItem.description,
                    categoryId: this.curItem.categoryId,
                    forceQuantity: this.curItem.forceQuantity,
                    unitPrice: this.curItem.unitPrice,
                    isActive: this.curItem.isActive,
                    isRequired: this.curItem.isRequired,
                    constraintType: this.curItem.constraintType,
                    allowableCompaniesId: this.curItem.allowableCompaniesId,
                    '3dModel': this.curItem['3dModel'],
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
                    }
                })
            },
            initData() {
                if (this.id) {
                    apiOptionCatalog.get({
                        id: this.id,
                        query: {
                            include: [
                                'files', 'category', 'allowable_companies'
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
                            this.$set(this.curItem, 'allowableCompaniesId', _.map(this.curItem.allowableCompanies, 'companyId'))
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
                    apiOptionCatalog.categories({}),
                    apiOptionCatalog.getActiveFlags({}),
                    apiOptionCatalog.getForceQuantityFlags({}),
                    apiOptionCatalog.getConstraintTypes({}),
                    apiOptionCatalog.getManufacturerCompanies({})
                ]

                return Promise.all(datas)
                    .then(response => {
                        this.optionCategories = response[0].data
                        this.activeFlags = response[1].data
                        this.forceQuantityFlags = response[2].data
                        this.constraintTypes = response[3].data
                        this.companies = response[4].data
                        return response
                    })
            },
            dataAllReady() {
                this.$nextTick(() => {
                    this.initMultiSelect(this.$refs.allowableCompanies, 'allowableCompaniesId')
                })
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>
.form-inline .form-control {
    width: 100%
}
</style>