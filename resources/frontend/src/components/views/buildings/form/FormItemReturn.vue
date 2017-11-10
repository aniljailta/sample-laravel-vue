<template>

    <div>
        <data-process v-bind:process="dataProcess" v-bind:with_loader="true"></data-process>
        <form v-if="dataIsReady">
            <div class="form-group">
                <div class="row col-xs-12 col-sm-12">
                    <div class="col-xs-12 col-md-3">
                        <label for="rto_company_id" class="control-label">RTO Contract Owner</label>
                        <select id="rto_company_id"
                                name="rto_company_id"
                                class="form-control"
                                v-model="curItem.usedRtoOwner"
                                initial="off">
                            <option v-bind:value="null" selected="selected"></option>
                            <option v-for="rtoCompany in rtoCompanies"
                                    v-bind:value="rtoCompany.id"
                                    v-bind:selected="curItem.usedRtoOwner && curItem.usedRtoOwner == rtoCompany.id">
                                {{ rtoCompany.name }}
                            </option>
                        </select>
                    </div>
                    <div class="col-xs-12 col-md-3">
                        <label for="date_building_returned" class="control-label">Date Building Returned</label>
                        <date :minDate="dateOrderSubmitted" id="date_building_returned" :value="curItem.dateBuildingReturned"></date>
                    </div>
                    <div class="col-xs-12 col-md-3">
                        <label for="dealer_location" class="control-label">Dealer Location</label>
                        <async-search-text
                            id="dealer_location"
                            :is-loading="locationNamesLoading"
                            :autocomplete-values="locationNames"
                            :block-width="'320px'"
                            @fetch-autocomplete="fetchLocations"
                            v-bind:item="{id:curItem.lastLocationId, name: lastLocationName}">
                        </async-search-text>
                    </div>
                    <div class="col-xs-12">
                        <label for="total_price" class="control-label">Total Price</label>
                        <div>
                            <span id="total_price" class="label label-success" style="font-size: 100%">{{ filters.money(totalPrice) }}</span>
                        </div>
                    </div>
                </div>
                <!-- common -->
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
                                ref="optionManager"
                                v-bind:total.sync="totalOptions"
                                v-bind:options="options"
                                v-bind:building-options="curItem.buildingOptions"
                                v-bind:building-model="curItem.buildingModel"
                                v-bind:editable="false"
                                v-bind:show-total-options="false"
                                v-bind:force-constraints="optionForceConstraints"/>
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
    import moment from 'moment'
    import BaseDataItem from 'src/components/views/_base/Block/DataItem.vue'
    import Datepicker from 'src/components/ui/Datepicker.vue'
    import Date from 'src/components/ui/Date.vue'
    import AsyncSearchText from 'src/components/ui/AsyncSearchText.vue'
    import FileManager from 'src/components/views/partials/FileManager.vue'
    import OptionPicker from './Optionpicker.vue'
    import OptionManager from './OptionManager.vue'
    import manageOptionPickerDataMixin from 'src/components/ui/Optionpicker/manage-data-mixin.js'
//    import FormSearchAsyncText from 'src/components/views/_base/ListItems/search-forms/AsyncInput.vue'

    import apiBuildings from 'src/api/buildings'
    import apiOptions from 'src/api/options'
    import apiBuildingModels from 'src/api/building-models'
    import apiRtoCompanies from 'src/api/rto-companies'
    import apiOrders from 'src/api/orders'
    import apiLocations from 'src/api/locations'

    import objectToFormData from 'src/helpers/object-to-form-data'
    import convertKeys from 'convert-keys'

    export default {
        name: 'building-form',
        extends: BaseDataItem,
        mixins: [manageOptionPickerDataMixin],
        data() {
            return {
                // data dependency
                opts: {},
                buildingModels: {},
                options: {},
                optionCategories: {},
                rtoCompanies: {},
                curItem: {
                    shellPrice: 0, // should be reactive
                    buildingOptions: [],
                    buildingModelId: null,
                    usedRtoOwner: null,
                    lastLocationId: false
                },
                dateOrderSubmitted: false,
                totalOptions: 0,
                optionForceConstraints: false,
                // custom els
                alias: {
                    'buildingOptions': 'curItem.buildingOptions'
                },
                locationNamesLoading: false,
                locationNames: [],
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
                lastLocationName: false
            }
        },
        components: {
            FileManager,
            Datepicker,
            Date,
            OptionPicker,
            OptionManager,
            AsyncSearchText
        },
        created() {
            this.$on('update-date', (val) => {
                this.curItem.dateBuildingReturned = val
            })
            this.$on('update-async', (val) => {
                this.curItem.lastLocationId = val.id
                this.lastLocationName = val.name
            })
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
        methods: {
            save({ item, data }) {
                return apiBuildings.save({ item, data }).then(response => response.data)
            },
            submit() {
                let self = this

                let item = _.merge({}, {
                    buildingModelId: this.curItem.buildingModelId,
                    notes: this.curItem.notes || null,
                    usedRtoOwner: this.curItem.usedRtoOwner,
                    dateBuildingReturned: moment(this.curItem.dateBuildingReturned).format('YYYY-MM-DD'),
                    newLocationId: this.curItem.lastLocationId
                }, {
                    buildingOptions: this.$refs.optionManager.getOptions()
                })

                if (this.curItem.id) item.id = this.curItem.id

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
                                'building_options.colors',
                                'files',
                                'last_location.location'
                            ]
                        }
                    })
                        .then(response => {
                            return this.initDependencies().then(() => response)
                        })
                        .then(response => {
                            let item = response.data
                            this.lastLocationName = false
                            this.curItem = _.cloneDeep(item)
                            this.curItem.lastLocationId = false
                            this.curItem.dateBuildingReturned = this.curItem.dateBuildingReturned ? moment(this.curItem.dateBuildingReturned).format('MM-DD-YYYY') : null
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
            fetchLocations(searchKeyword) {
                this.locationNamesLoading = true
                let query = {
                    fields: ['id', 'name'],
                    per_page: 9999,
                    where: {
                        category: 'dealer'
                    }
                }
                let searchQuery = '%' + searchKeyword + '%'
                _.set(query, ['where', 'name', 'like'], searchQuery)
                const namesData = apiLocations.get({
                    query
                })

                return Promise.resolve(namesData)
                    .then(response => {
                        let customerNamesStructured = []
                        const dataNotStructured = response.body.data
                        for (let item of dataNotStructured) {
                            customerNamesStructured.push({id: item.id, name: item.name})
                        }
                        this.locationNamesLoading = false
                        this.locationNames = customerNamesStructured
                    })
                    .catch(response => {
                        this.$emit('data-failed', response)
                    })
            },
            initDependencies() {
                const datas = [
                    apiOptions.categories({}),
                    apiOptions.get({
                        query: {
                            per_page: 99999,
                            where: {
                                is_active: 'yes'
                            },
                            include: {
                                category: {
                                    where: {
                                        group: 'discounts'
                                    }
                                },
                                files: true,
                                allowable_models: {
                                    where: {
                                        is_active: 'yes'
                                    },
                                    fields: ['id']
                                },
                                allowable_colors: true
                            }
                        }
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
                    apiRtoCompanies.getRtoCompanies(),
                    apiOrders.get({
                        query: {
                            per_page: 99999,
                            where: {
                                building_id: this.id
                            }
                        }
                    })
                ]

                return Promise.all(datas)
                    .then(response => {
                        this.optionCategories = response[0].data
                        this.options = response[1].data.data
                        let clearedOptions = []
                        for (let option of this.options) {
                            if (option.category !== null) {
                                clearedOptions.push(option)
                            }
                        }
                        this.options = clearedOptions
                        this.buildingModels = response[2].data.data
                        this.rtoCompanies = response[3].data.data
                        this.dateOrderSubmitted = response[4].data.data[0] !== undefined ? moment(response[4].data.data[0].dateSubmitted).format('MM/DD/YYYY') : false
                        return response
                    })
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