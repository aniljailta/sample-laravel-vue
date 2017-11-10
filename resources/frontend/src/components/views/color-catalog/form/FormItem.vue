<template>

    <div>
        <data-process v-bind:process="dataProcess" v-bind:with_loader="true"></data-process>
        <div v-if="dataIsReady">
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
                        <input id="name" placeholder="Name" type="text" class="form-control" v-model="curItem.name">
                    </div>

                    <div class="col-xs-12 col-md-4">
                        <label for="type" class="control-label">Type</label>
                        <select id="type"
                                name="type"
                                class="form-control"
                                v-model="curItem.type"
                                initial="off">
                            <option v-for="(value, key) in types"
                                    v-bind:value="key"
                                    v-bind:selected="curItem.value && curItem.value == key">
                                {{ value }}
                            </option>
                        </select>
                    </div>

                    <div class="col-xs-12 col-md-4">
                        <label for="hex" class="control-label">Hex Code (with #)</label>
                        <input id="hex" placeholder="Hex Code" type="text" class="form-control" v-model="curItem.hex">
                    </div>
                </div>

                <div class="row col-xs-12 col-sm-12">
                    <div class="col-xs-12 col-md-6">
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

                    <div class="col-xs-12 col-md-5">
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

                    <div class="row col-xs-12 col-sm-12">
                        <div class="col-xs-12 col-sm-4">
                            <label>Image</label>
                            <file-manager class="file-manager__container"
                                          ref="fileManager"
                                          :attachment="image"
                                          :uploadAsync="uploadAsync"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script type="text/babel">
    import BaseDataItem from 'src/components/views/_base/Block/DataItem.vue'
    import FileManager from './FileManager.vue'

    import objectToFormData from 'src/helpers/object-to-form-data'
    import convertKeys from 'convert-keys'

    import 'bootstrap-multiselect'
    import 'bootstrap-multiselect/dist/js/bootstrap-multiselect-collapsible-groups.js'
    import '!style!css!less!bootstrap-multiselect/dist/css/bootstrap-multiselect.css'

    import apiColorCatalog from 'src/api/color-catalog'

    export default {
        name: 'style-form-item',
        extends: BaseDataItem,
        data() {
            return {
                activeFlags: {},
                types: {},
                curItem: {
                    id: null,
                    name: null,
                    type: null,
                    hex: null,
                    isActive: 'yes',
                    allowableCompaniesId: []
                }
            }
        },
        computed: {
            id() {
                if (!_.isUndefined(this.item.id)) {
                    return this.item.id
                }
                return null
            },
            image() {
                let attachment = {
                    canDelete: true,
                    canUpload: true,
                    categoryId: 'color_catalog_image',
                    storableId: this.item.id,
                    files: []
                }

                if (this.curItem.image) {
                    attachment.files = [this.curItem.image]
                }

                return attachment
            },
            uploadAsync() {
                if (this.curItem.id) {
                    return true
                }
                return false
            }
        },
        components: {
            FileManager
        },
        methods: {
            save({ item, data }) {
                return apiColorCatalog.save({ item, data }).then(response => response.data)
            },
            submit() {
                let self = this
                let item = _.merge({}, {
                    name: this.curItem.name,
                    type: this.curItem.type,
                    hex: this.curItem.hex,
                    allowableCompaniesId: this.curItem.allowableCompaniesId,
                    isActive: this.curItem.isActive
                }, {
                    upload_files: this.$refs.fileManager.$refs.uploadInput[0].files
                })

                if (this.curItem.id) item.id = this.curItem.id

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
                    apiColorCatalog.get({
                        id: this.id,
                        query: {
                            include: [
                                'image', 'allowable_companies'
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
                    apiColorCatalog.getActiveFlags({}),
                    apiColorCatalog.getTypes({}),
                    apiColorCatalog.getManufacturerCompanies({})
                ]

                return Promise.all(datas)
                    .then(response => {
                        this.activeFlags = response[0].data
                        this.types = response[1].data
                        this.companies = response[2].data
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

</style>