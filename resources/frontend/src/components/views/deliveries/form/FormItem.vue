<template>

    <div class="form-horizontal">
        <data-process v-bind:process="dataProcess" v-bind:with_loader="true"></data-process>

        <form v-if="dataIsReady">
            <div class="form-group">
                <div class="row col-xs-12">
                     <div class="col-xs-12 col-md-4">
                         <label for="category" class="control-label">Category</label>
                         <select id="category"
                                 name="category"
                                 class="form-control"
                                 initial="off"
                                 v-model="curItem.categoryId">
                             <option v-for="(category, cat_id) in categories"
                                     v-bind:value="category.id"
                                     v-bind:selected="curItem.categoryId == category.id">
                                 {{ category.name }}
                             </option>
                         </select>
                     </div>
                    <div class="col-xs-12 col-md-4">
                        <label for="status_id" class="control-label">Status</label>
                        <select id="status_id"
                                name="status"
                                class="form-control"
                                initial="off"
                                v-model="curItem.statusId">
                            <option v-for="(status, status_id) in statuses" 
                                    v-bind:value="status.id"
                                    v-bind:selected="curItem.statusId == status.id">
                                {{ status.name }}
                            </option>
                        </select>
                    </div>

                     <div class="col-xs-12 col-md-4">
                         <label for="priority" class="control-label">Priority</label>
                         <select id="priority"
                                 name="priority"
                                 class="form-control"
                                 initial="off"
                                 v-model="curItem.priorityId">
                             <option v-for="(priority, p_id) in priorities"
                                     v-bind:value="priority.id"
                                     v-bind:selected="curItem.priorityId == priority.id">
                                 {{ priority.name }}
                             </option>
                         </select>
                     </div>
                </div>
                <div class="row col-xs-12">
                    <div class="col-xs-6 col-md-6" v-show="!isCustomerDelivery">
                        <label for="building_id" class="control-label">Building</label>
                        <select id="building_id"
                                name="building_id"
                                class="form-control"
                                initial="off"
                                v-on:change="applyBuildingParams($event.target.value)"
                                v-model="curItem.buildingId">
                            <option :value="null" disabled>Select...</option>
                            <option v-for="(building, building_id) in buildings"
                                    v-bind:value="building.id">#{{ building.id }} - {{ building.serialNumber }}</option>
                        </select>
                    </div>
                    <div class="col-xs-6 col-md-6" v-show="isCustomerDelivery">
                        <label for="sale_id" class="control-label">Sales</label>
                        <select id="sale_id"
                                name="sale_id"
                                class="form-control"
                                initial="off"
                                v-on:change="applySaleParams(sale)"
                                v-model="curItem.saleId">
                            <option :value="null" selected>Select...({{ sales.length }})</option>
                            <option v-for="(sale, sale_id) in sales"
                                    v-bind:value="sale.id">{{ makeSaleTitle(sale)}}</option>
                        </select>
                    </div>
                    <div class="col-xs-6 col-md-6">
                        <label for="promised_by_date" class="control-label">Promised By Date : {{ curItem.promisedByDate }} </label>
                    </div>
                </div>

                <div class="row col-xs-12">
                    <div class="col-xs-12 col-sm-4">
                        <label for="driver_id" class="control-label">Driver</label>
                        <select id="driver_id"
                                name="driver_id"
                                class="form-control"
                                initial="off"
                                v-model="curItem.driverId">
                            <option :value="null" selected>Select...({{ drivers.length }})</option>
                            <option v-for="(driver, d_id) in drivers"
                                    v-bind:value="driver.id">#{{ driver.id }} - {{ driver.fullName }}</option>
                        </select>
                    </div>
                     <div class="col-xs-12 col-sm-4">
                        <label for="truck_id" class="control-label">Truck</label>
                        <select id="truck_id"
                                name="truck_id"
                                class="form-control"
                                initial="off"
                                v-model="curItem.truckId">
                            <option :value="null" selected>Select...({{ trucks.length }})</option>
                            <option v-for="(truck, t_id) in trucks"
                                    v-bind:value="truck.id"
                                     v-bind:selected="curItem.truckId == truck.id"
                                    >#{{ truck.name }}</option>
                        </select>
                    </div>
                     <div class="col-xs-12 col-sm-4">
                        <label for="trailer_id" class="control-label">Trailer</label>
                        <select id="trailer_id"
                                name="trailer_id"
                                class="form-control"
                                initial="off"
                                v-model="curItem.trailerId">
                            <option :value="null" selected>Select...({{ trailers.length }})</option>
                            <option v-for="(trailer, t_id) in trailers"
                                    v-bind:value="trailer.id"
                                     v-bind:selected="curItem.trailerId == trailer.id"
                                    >#{{ trailer.name }}</option>
                        </select>
                    </div>
                </div>
                <div class="row col-xs-12">
                    <div class="col-xs-12 col-md-6">
                        <label for="trailer_id" class="control-label">Start Location</label>
                        <span>{{startLocationName}}</span>
                    </div>
                    <div class="col-xs-12 col-md-5 form-inline">
                        <label for="trailer_id" class="control-label">End Location</label>
                        <select id="end_location_id"
                                name="end_location_id"
                                class="form-control"
                                initial="off"
                                v-model="curItem.endLocationId">
                            <option :value="null">Select...</option>
                            <option v-for="location in locations"
                                    v-bind:value="location.id">{{ location.name }}</option>
                        </select>
                        <button type="button"
                                class="btn btn-primary btn"
                                title="Add option"
                                v-on:click.prevent="openCreateLocationModal">
                            <i class="fa fa-plus" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
                <div class="row col-xs-12">
                    <div class="col-xs-12 col-md-3">
                        <label for="scheduled_date" class="control-label">Scheduled Date</label>
                        <datepicker :width="'100%'" id="scheduled_date" :value="curItem.scheduledDate" ref="scheduledDate"></datepicker>
                    </div>
                    <div class="col-xs-12 col-md-3">
                        <label for="confirmed_date" class="control-label">Completed Date</label>
                        <datepicker :width="'100%'" id="confirmed_date" :value="curItem.completedDate" ref=completedDate></datepicker>
                    </div>
                    <div class="col-xs-12 col-md-3">
                        <label for="start_time" class="control-label">Start Time</label>
                        <datepicker :width="'100%'" id="start_time" :value="curItem.startTime" ref="startTime"></datepicker>
                    </div>
                    <div class="col-xs-12 col-md-3">
                        <label for="end_time" class="control-label">End Time</label>
                        <datepicker :width="'100%'" id="end_time" :value="curItem.endTime" ref="endTime"></datepicker>
                    </div>
                </div>

                <div class="row col-xs-12">

                    <div class="col-xs-12 col-md-3">
                        <label for="setup_duration" class="control-label">Setup Duration(minutes)</label>
                        <input class="form-control" placeholder="Setup Duration" name="setup_duration" type="number" step="30" id="setup_duration" v-model="curItem.setupDuration">
                    </div>
                    <div class="col-xs-12 col-md-3">
                        <label for="average_drive_speed" class="control-label">Average Drive Speed(MPH)</label>
                        <input class="form-control" placeholder="Average drive speed in mph" name="average_drive_speed" type="number" min="15" max="70" step="5" id="average_drive_speed" v-model="curItem.averageDriveSpeed">
                    </div>
                    <div class="col-xs-12 col-md-3">
                        <label for="confirmed_distance" class="control-label">Confirm Distance</label>
                        <input class="form-control" placeholder="Setup Duration" name="confirmed_distance" type="number" id="confirmed_distance" v-model="curItem.confirmedDistance">
                    </div>
                    <div class="col-xs-12 col-md-3">
                        <label for="cost" class="control-label">Cost</label>
                        <input class="form-control" placeholder="Cost $" name="Cost" type="number" id="cost" v-model="curItem.cost">
                    </div>
                </div>

                <div class="row col-xs-12">
                    <div class="col-xs-12 col-md-12">
                        <label for="notes" class="control-label">Notes</label>
                        <textarea class="form-control" placeholder="" name="notes" id="notes" v-model="curItem.notes"></textarea>
                    </div>
                </div>
            </div>
        </form>

        <component v-if="createLocationModal" :show="createLocationModal"
                   is="LocationModalCreate"
                   v-bind:item="{}"
                   v-on:close="closeCreateLocationModal"
                   v-on:saved="pickNewLocation">
        </component>
    </div>

</template>

<script type="text/babel">
    import BaseDataItem from 'src/components/views/_base/Block/DataItem.vue'
    import Datepicker from 'src/components/ui/Datepicker.vue'
    // import LocationModalCreate from 'src/components/views/locations/modals/LocationModalCreate.vue'

    import apiDeliveries from 'src/api/deliveries'
    import apisMixin from './apis-mixin'

    export default {
        name: 'delivery-form',
        extends: BaseDataItem,
        mixins: [apisMixin],
        data() {
            return {
                createLocationModal: false,
                curItem: {
                    promisedByDate: null,
                    scheduledDate: null,
                    completedDate: null,
                    saleId: null,
                    buildingId: null,
                    startLocationId: null,
                    endLocationId: null,
                    startTime: null,
                    endTime: null,
                    cost: null,
                    driverId: null,
                    truckId: null,
                    trailerId: null,
                    statusId: 'pending',
                    categoryId: null,
                    priorityId: null,
                    confirmedDistance: null,
                    setupDuration: null,
                    averageDriveSpeed: null,
                    notes: null
                },
                // data dependency
                statuses: {},
                categories: {},
                priorities: {},
                buildings: {},
                sales: {},
                locations: {},
                drivers: {},
                trucks: {},
                trailers: {}
            }
        },
        components: {
            Datepicker,
            LocationModalCreate: function(resolve) {
                require(['../../locations/modals/ModalCreate.vue'], resolve)
            }
        },
        methods: {
            save({ item, data }) {
                return apiDeliveries.save({ item, data }).then(response => response.data)
            },
            initData() {
                if (this.item.id) {
                    apiDeliveries.get({
                        id: this.item.id,
                        query: {
                                include: [
                                'building',
                                'start_location',
                                'end_location',
                                'driver',
                                'truck',
                                'trailer'
                                ]
                        }
                    })
                        .then(response => {
                            return this.initDependencies().then(() => response)
                        })
                        .then(response => {
                            let item = response.data
                            this.curItem = _.cloneDeep(item)
                            this.curItem.scheduledDate = this.filters.moment(this.curItem.scheduledDate, 'YYYY-MM-DD', 'MM/DD/YYYY')
                            this.curItem.completedDate = this.filters.moment(this.curItem.completedDate, 'YYYY-MM-DD', 'MM/DD/YYYY')
                            this.curItem.startTime = this.filters.moment(this.curItem.startTime, 'YYYY-MM-DD', 'MM/DD/YYYY')
                            this.curItem.endTime = this.filters.moment(this.curItem.endTime, 'YYYY-MM-DD', 'MM/DD/YYYY')
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
                    datas.push(this.receiveStatuses())
                    datas.push(this.receiveBuildings())
                    datas.push(this.receiveLocations())
                    datas.push(this.receiveCategories())
                    datas.push(this.receivePriorities())
                    datas.push(this.receiveDrivers())
                    datas.push(this.receiveTrucks())
                    datas.push(this.receiveTrailers())
                    datas.push(this.receiveSales())
                }

                if (dep === 'locations') {
                    datas.push(this.receiveLocations())
                }

                return Promise.all(datas)
                    .then(response => {
                        if (dep === null) {
                            this.statuses = response[0].data
                            this.buildings = response[1].data.data
                            this.locations = response[2].data.data
                            this.categories = response[3].data
                            this.priorities = response[4].data
                            this.drivers = response[5].data.data
                            this.trucks = response[6].data.data
                            this.trailers = response[7].data.data
                            this.sales = response[8].data.data
                        }

                        if (dep === 'locations') {
                            this.locations = response[0].data.data
                        }
                        return response
                    })
            },
            submit() {
                let form = this.curItem
                if (this.curItem.id) form._method = 'put'

                if (this.$refs.scheduledDate.val) form.scheduled_date = this.filters.moment(this.$refs.scheduledDate.val, 'MM/DD/YYYY', 'YYYY-MM-DD')
                if (this.$refs.completedDate.val) form.completed_date = this.filters.moment(this.$refs.completedDate.val, 'MM/DD/YYYY', 'YYYY-MM-DD')
                if (this.$refs.startTime.val) form.start_time = this.filters.moment(this.$refs.startTime.val, 'MM/DD/YYYY', 'YYYY-MM-DD')
                if (this.$refs.endTime.val) form.end_time = this.filters.moment(this.$refs.endTime.val, 'MM/DD/YYYY', 'YYYY-MM-DD')

                this.run({text: 'Saving..', type: 'form'})
                return this.save({ item: form, data: form })
                    .then(msg => {
                        this.$emit('data-process-update', {
                            running: false,
                            success: msg
                        })
                        this.$emit('item-saved')
                    })
                    .catch(response => {
                        this.$emit('data-failed', response)
                    })
            },
            openCreateLocationModal() {
                this.createLocationModal = true
            },
            closeCreateLocationModal() {
                this.createLocationModal = false
            },
            pickNewLocation(newLocation) {
                return new Promise(this.receiveLocations).then(resolve => {
                    this.$set(this.curItem, 'endLocationId', newLocation.id)
                    resolve()
                }).catch(response => {
                    this.$emit('data-failed', response)
                })
            },
            applyBuildingParams(buildingID) {
                let found = _.find(this.buildings, {id: parseInt(buildingID)})
                if (found) {
                    // let building = _.cloneDeep(found)
                    if (found.lastLocation) {
                        this.curItem.startLocationId = found.lastLocation.locationId
                        this.curItem.saleId = null
                        this.curItem.promisedByDate = null
                    }
                }
            },
            applySaleParams(sale) {
                if (!sale) return
                this.$set(this.curItem, 'saleId', sale.id)

                if (sale.building.lastLocation) {
                    this.curItem.startLocationId = sale.building.lastLocation.locationId
                    this.curItem.buildingId = sale.buildingId
                }
                if (sale.order) {
                   this.$set(this.curItem, 'promisedByDate', sale.order.cedEnd)
                }
            },
            makeSaleTitle(sale) {
                let title = ''

                if (sale.order.orderReference) {
                    title = sale.order.orderReference.firstName + ' - ' + sale.order.orderReference.lastName
                }

                if (sale.building) {
                    title = title + ' - ' + sale.building.serialNumber
                }
                return title
            }
        },
        computed: {
            building() {
                let building = null
                if (this.curItem.buildingId) {
                    let buildingID = this.curItem.buildingId
                    let found = _.find(this.buildings, {id: buildingID})
                    if (found) {
                        building = _.cloneDeep(found)
                    }
                }
                return building
            },
            buildingCurrentLocation() {
                if (!this.building || !this.building.lastLocation) return null
                let found = _.find(this.locations, {id: this.building.lastLocation.locationId})
                return found
            },
            buildingSaleLocation() {
                if (!this.sale) return null
                let found = _.find(this.locations, {id: this.sale.locationId})
                return found
            },
            sale() {
                if (!this.curItem.saleId) return null
                let sale = _.find(this.sales, {id: this.curItem.saleId})
                return sale
            },
            isCustomerDelivery() {
               return this.curItem.categoryId === 'customer_delivery'
            },
            startLocationName() {
                let found = _.find(this.locations, {id: this.curItem.startLocationId})
                if (found) return found.name
                return null
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>
    .datepicker{
        position: relative;
        display: block !important;
        padding: 0;
    }
</style>