<template>

    <section class="panel-featured">
        <header class="panel-heading panel-with-actions" v-if="curItem.id">
            <h2 class="panel-title">
                <span>Building # {{ curItem.id }}</span>

                <template v-if="curItem.id">
                    <tooltip effect="scale"
                             placement="right"
                             content="Sale generated"
                             v-if="curItem.currentOrder && curItem.currentOrder.statusId === 'sale_generated' ">
                        <button class="btn btn-default btn-md"
                                v-on:click.prevent="openUpdateModal"
                                v-bind:disabled="curItem.currentOrder && curItem.currentOrder.statusId === 'sale_generated' ">
                            <i class="fa fa-pencil"></i> Edit
                        </button>
                    </tooltip>
                    <button class="btn btn-default btn-md"
                            v-else
                            v-on:click.prevent="openUpdateModal">
                        <i class="fa fa-pencil"></i> Edit
                    </button>
                </template>
            </h2>
            <div class="clearfix"></div>
        </header>

        <div class="panel-body">
            <data-process v-bind:process="dataProcess" v-bind:with_loader="true"></data-process>

            <template v-if="dataIsReady">
                <!-- BUILDING DETAILS -->
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                        <section class="panel sub-panel">
                            <header class="panel-heading">
                                <h4 class="panel-title">Building Details</h4>
                            </header>
                            <div class="panel-body">
                                <dl class="dl-horizontal mb-none">
                                    <dt>Created at</dt>
                                    <dd>{{ filters.moment(curItem.createdAt, 'YYYY-MM-DD HH:mm:ss', 'MM/DD/YYYY HH:mm:ss') || '-' }}</dd>

                                    <dt>Updated at</dt>
                                    <dd>{{ filters.moment(curItem.updatedAt, 'YYYY-MM-DD HH:mm:ss', 'MM/DD/YYYY HH:mm:ss') || '-' }}</dd>
                                </dl>
                                <dl class="dl-horizontal mb-none">
                                    <dt>Serial #</dt>
                                    <dd><span class="label label-info">{{ curItem.serialNumber || '-' }}</span></dd>

                                    <dt>Manufacturing Plant</dt>
                                    <dd>
                                        <span v-if="curItem.plant">{{ curItem.plant.name }}</span>
                                        <span v-else>-</span>
                                    </dd>

                                    <dt>Building Model</dt>
                                    <dd>
                                        <span v-if="curItem.buildingModel">{{ curItem.buildingModel.name }} ({{ filters.money(curItem.buildingModel.shellPrice) }})</span>
                                        <span v-else>-</span>
                                    </dd>

                                    <dt>Width</dt>
                                    <dd>{{ curItem.width || '-' }}</dd>

                                    <dt>Length</dt>
                                    <dd>{{ curItem.length || '-' }}</dd>

                                    <dt>Height</dt>
                                    <dd>{{ curItem.height || '-' }}</dd>
                                </dl>
                            </div>
                            <div class="panel-footer">
                                <dl class="dl-horizontal mb-none">
                                    <dt>Shell Price</dt>
                                    <dd><span class="label label-success">{{ filters.money(curItem.shellPrice) }}</span></dd>
                                    <dt>Options</dt>
                                    <dd><span class="label label-success">{{ filters.money(curItem.totalOptions) }}</span></dd>
                                    <dt>Total Price</dt>
                                    <dd><span class="label label-success">{{ filters.money(curItem.totalPrice) }}</span></dd>
                                </dl>
                            </div>
                        </section>

                        <section class="panel sub-panel">
                            <header class="panel-heading">
                                <h4 class="panel-title">Options</h4>
                            </header>
                            <div class="panel-body">
                                <div class="col-xs-12">
                                    <option-viewer ref="optionViewer"
                                                   v-bind:total-options="curItem.totalOptions"
                                                   v-bind:building-options="curItem.buildingOptions">
                                    </option-viewer>
                                </div>
                            </div>
                        </section>

                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
                        <!-- DOCUMENTS -->
                        <section class="panel sub-panel">
                            <header class="panel-heading">
                                <h4 class="panel-title">Documents</h4>
                            </header>
                            <div class="panel-body">
                                <inventory-form :id="curItem.id" v-if="curItem.id"></inventory-form>
                                <a class="btn btn-md btn-primary" v-bind:href="'/buildings/' + curItem.id + '/work-order'" target="_blank">
                                    <i class="fa fa-file-pdf-o" aria-hidden="true"></i> Work Order
                                </a>
                                <a class="btn btn-md btn-primary" v-bind:href="'/buildings/' + curItem.id + '/customer-order'" target="_blank" v-if="curItem.currentOrder">
                                    <i class="fa fa-file-pdf-o" aria-hidden="true"></i> Customer Order
                                </a>
                                <hr>
                                <!-- Qr code Build status -->
                                    <qr-codes-build :item="{id:curItem.id, sn:curItem.serialNumber}"></qr-codes-build>
                                <!-- Qr code location-->
                                    <qr-codes-location :item="{id:curItem.id, sn:curItem.serialNumber}"></qr-codes-location>
                            </div>
                        </section>

                        <!-- LAST STATUS -->
                        <status :status="curItem.lastStatus"></status>
                        <!-- LAST LOCATION -->
                        <location :location="curItem.lastLocation"></location>

                        <!-- NOTES -->
                        <section class="panel sub-panel">
                            <header class="panel-heading">
                                <h4 class="panel-title">Notes</h4>
                            </header>
                            <div class="panel-body">
                                {{ curItem.notes || '-' }}
                            </div>
                        </section>
                    </div>
                </div>

                <!-- HISTORY -->
                <section class="panel sub-panel">
                    <header class="panel-heading">
                        <h4 class="panel-title">Status History</h4>
                    </header>
                    <div class="panel-body">
                        <status-history :status-history="curItem.buildingHistory"></status-history>
                    </div>
                </section>

                <!-- LOCATIONS -->
                <section class="panel sub-panel">
                    <header class="panel-heading">
                        <h4 class="panel-title">Location History</h4>
                    </header>
                    <div class="panel-body">
                        <building-locations :building-locations="curItem.buildingLocations"></building-locations>
                    </div>
                </section>

                <!-- FILES -->
                <section class="panel sub-panel">
                    <header class="panel-heading">
                        <h4 class="panel-title">Files</h4>
                    </header>
                    <div class="panel-body">
                        <file-manager ref="fileManager"
                                      v-bind:options="fileManager"
                                      v-bind:storable_type="'building'"
                                      v-bind:storable_id="curItem.id ? curItem.id : null"
                                      v-bind:files="curItem.files">
                        </file-manager>
                    </div>
                </section>
            </template>
        </div>

        <modal-update v-if="modal !== null && modal.mode === 'edit' " :show="true"
                      v-on:close="closeModalUpdate"
                      v-on:saved="refresh"
                      :item="modal !== null && modal.mode === 'edit' ? modal.item : null"></modal-update>
    </section>

</template>

<script type="text/babel">
    import BaseDataItem from 'src/components/views/_base/Block/DataItem.vue'
    import apiBuildings from 'src/api/buildings'
    import Tooltip from 'vue-strap/src/Tooltip.vue'

    import Datepicker from 'src/components/ui/Datepicker.vue'
    import FileManager from 'src/components/views/partials/FileManager.vue'
    import OptionViewer from 'src/components/views/partials/OptionViewer/OptionViewer.vue'
    // statuses
    import Status from './Status.vue'
    import StatusHistory from './building-history/StatusHistory.vue'
    // locations
    import Location from './Location.vue'
    import BuildingLocations from './building-locations/BuildingLocations.vue'
    import InventoryForm from './InventoryForm.vue'
    // qrcodes
    import QrCodesBuild from '../qr-codes/QrCodeBuild.vue'
    import QrCodesLocation from '../qr-codes/QrCodeLocation.vue'

    // modals
    import crudModalsMixin from 'src/components/views/_base/ListItems/_mixins/crud-modals'
    import ModalUpdate from '../modals/ModalUpdate.vue'
    import ModalCreate from '../qr-codes/modals/ModalCreate.vue'
    
    export default {
        name: 'building-show',
        extends: BaseDataItem,
        mixins: [crudModalsMixin],
        data() {
            return {
                modal: null,
                curItem: {},
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
        props: ['id'],
        components: {
            Tooltip,
            ModalUpdate,
            ModalCreate,
            FileManager,
            Datepicker,
            OptionViewer,
            Status,
            StatusHistory,
            Location,
            BuildingLocations,
            InventoryForm,
            QrCodesBuild,
            QrCodesLocation
        },
        computed: {},
        methods: {
            openUpdateModal() {
                this.modal = {
                    mode: 'edit',
                    item: _.cloneDeep(this.curItem)
                }
            },
            refresh(part) {
                if (!part) {
                    this.run({type: 'refresh'})
                    this.initData(this.id)
                    return
                }

                if (part === 'building-locations') {
                    apiBuildings.get({
                        id: this.id,
                        query: {
                            include: [
                                'building_locations',
                                'building_locations.location',
                                'building_locations.user',
                                'last_location',
                                'last_location.location',
                                'last_location.user'
                            ]
                        }
                    }).then((response) => {
                        let item = response.data
                        this.$set(this.curItem, 'buildingLocations', _.cloneDeep(item['buildingLocations']))
                        this.$set(this.curItem, 'lastLocation', _.cloneDeep(item['lastLocation']))
                        // this.$emit('data-ready')
                    })
                }

                if (part === 'building-history') {
                    apiBuildings.get({
                        id: this.id,
                        query: {
                            include: [
                                'building_history',
                                'building_history.building_status',
                                'building_history.user',
                                'building_history.expense',
                                'last_status',
                                'last_status.building_status',
                                'last_status.user'
                            ]
                        }
                    }).then((response) => {
                        let item = response.data
                        this.$set(this.curItem, 'buildingHistory', _.cloneDeep(item['buildingHistory']))
                        this.$set(this.curItem, 'lastStatus', _.cloneDeep(item['lastStatus']))
                        // this.$emit('data-ready')
                    })
                }
            },
            initData() {
                this.run({text: 'Loading..'})
                this.curItem = {}
                let id = this.id
                return apiBuildings.get({
                    id: id,
                    query: {
                        include: [
                            'plant',
                            'building_model',
                            'building_model.style',
                            'building_options',
                            'building_options.option',
                            'building_options.option.allowable_colors',
                            'building_options.colors',
                            'building_options.option_color.color.image',
                            'building_locations',
                            'building_locations.location',
                            'building_locations.user',
                            'last_location',
                            'last_location.location',
                            'last_location.user',
                            'building_history',
                            'building_history.building_status',
                            'building_history.user',
                            'building_history.expense',
                            'last_status',
                            'last_status.building_status',
                            'last_status.user',
                            'files',
                            'current_order'
                        ]
                    }
                }).then((response) => {
                    let item = response.data
                    this.id = id
                    this.curItem = _.cloneDeep(item)
                }).then(() => {
                    this.$emit('data-ready')
                }).catch((response) => {
                    this.$emit('data-failed', response)
                })
            }
        },
        watch: {
            '$route': function() {
                this.run({type: 'data'})
                this.initData()
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>
    .row {
        margin-bottom: 0.5em;
    }
   .action-list {
        padding-top: 3px;
    }
    .margin-top-10{
        margin-top:10px;
    }
</style>