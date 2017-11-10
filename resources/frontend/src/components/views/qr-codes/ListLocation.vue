<template>

    <div>
        <section class="panel-featured page-list-items">
            <header class="panel-heading clearfix" v-show="dataIsReady">
                <h2 class="panel-title">Location Status Update</h2>
            </header>

            <div class="panel-body overlayable">
                <data-process v-bind:process="dataProcess" v-bind:with_loader="true"></data-process>

                <div v-show="dataIsReady">
                    <div class="row">
                        <label class="col-sm-6 control-label">Building SN</label>
                        <span class="label label-info"> {{ curItem.sn }}</span>

                    </div>
                    <div>
                        <div class="row" v-if="saletype == 'sale'">
                            <label class="col-sm-6 control-label">Customer Name</label>
                            <span>{{locationdata.data.location.name}}</span>
                        </div>
                        <div class="row" v-if="saletype == 'sale'">
                            <label class="col-sm-6 control-label">Address</label>
                            <span>
                            <ul class="customerul">
                                <li>{{locationdata.data.location.address}}</li>
                                <li>{{locationdata.data.location.city}}  {{locationdata.data.location.state}} , {{locationdata.data.location.zip}}</li> 
                            </ul>
                            </span>

                        </div>
                        <div class="row">
                            <label class="col-sm-6 control-label">Image Count</label>
                            <span class="label label-info"><b> {{ locationdata.imagesCount }}</b></span>
                        </div>
                        <div class="row" v-if="saletype == 'non'">
                            <div class="col-xs-12 col-md-12">
                                <label for="status" class="control-label"> Dealer Location</label>
                                <select id="status"
                                        name="status"
                                        class="form-control"
                                        v-model="curItem.locationId"
                                        v-on:change="selectLocation($event.target.value)"
                                        initial="off">
                                    <option disabled value="">Please select one</option>

                                    <option v-for="location in locationdata.data"
                                            v-bind:value="location.id"
                                    >
                                        {{ location.name }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="row" v-if="saletype == 'sale'">
                            <div class="col-xs-12 col-sm-12">
                                <file-manager ref="fileManager"
                                              v-bind:options="fileManager"
                                              v-bind:storable_type="'location'"
                                              v-bind:storable_id="curItem ? curItem.buildingId : null"
                                              v-bind:preserve="latlongdata ? latlongdata.files : null"
                                              v-bind:upload_async="curItem.locationId ? true : false"
                                              v-bind:files="files">
                                </file-manager>
                            </div>
                        </div>
                        <div class="row" v-if="saletype == 'sale' && showLatLong">
                            <div class="col-xs-6 col-sm-6">
                                <label class="control-label">Latitude</label>
                                <input id="latitude" type="text" class="form-control" v-model="curItem.latitude"
                                       placeholder="Latitude" required>
                            </div>
                            <div class="col-xs-6 col-sm-6">
                                <label class="control-label">Longitude</label>
                                <input id="longitude" type="text" class="form-control" v-model="curItem.longitude"
                                       placeholder="Longitude" required>
                            </div>
                            <div class="col-xs-12 col-sm-12">
                                <h6 class="red"><sup>*</sup> {{latlongdata.type}} </h6>
                            </div>
                            <div class="col-xs-12 col-md-12">
                                <label for="Notes" class="control-label">Notes</label>
                                <textarea id="Notes" type="text" class="form-control" v-model="curItem.notes"></textarea>
                        </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 margin-top-10">
                            <button v-if="saletype == 'non'" v-on:click="uploadlocation('update')" class="btn btn-warning btn-md"
                                    :disabled="saletype == 'sale' ? true : false">Update Location
                            </button>
                            <button v-if="saletype == 'sale' && showLatLong" v-on:click="updatelatlong()" class="btn btn-warning btn-md">Submit Latitude and Longitude for this Location
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script type="text/babel">
    import BaseDataItem from 'src/components/views/_base/Block/DataItem.vue'
    import FileManager from 'src/components/views/partials/FileManager.vue'
    import apiQrCode from 'src/api/qrcodes'
    import BuildingStatusLabel from 'src/components/views/partials/BuildingStatusLabel.vue'
    import objectToFormData from 'src/helpers/object-to-form-data'
    import convertKeys from 'convert-keys'

    export default {
        extends: BaseDataItem,
        name: 'qrcode-list-page',
        data() {
            return {
                curItem: {},
                saletype: '',
                locationdata: [],
                showLatLong: false,
                latlongdata: {},
                files: [],
                fileManager: {
                    browseOnZoneClick: false,
                    dropZoneEnabled: true,
                    showPreview: true,
                    showUpload: false,
                    showBrowse: true,
                    showCaption: true,
                    uploadAsync: false,
                    autoReplace: true,
                    maxFileCount: 1,
                    uploadUrl: '/api/qr-codes/location-file',
                    deleteUrl: '/api/files/'
                }
            }
        },
        props: {
            status: {
                default() {
                    return {}
                }
            },
            identifier: null
        },
        components: {
            BuildingStatusLabel,
            FileManager
        },
        updated() {
            this.watchforFileEvent()
        },
        methods: {
            initData() {
                this.run({text: 'Loading..'})
                this.curItem.identifier = this.identifier
                apiQrCode.getByIdentifierLocation({
                    query: {
                        identifier: this.curItem.identifier
                    }
                }).then(response => {
                    this.saletype = response.data.status
                    this.locationdata = response.data.item
                    this.curItem.locationId = this.locationdata.data.locationId
                    this.curItem.buildingId = this.locationdata.building.id
                    this.curItem.sn = this.locationdata.building.sn
                    this.$emit('data-process-update', {running: false})
                }).catch(response => {
                    this.$emit('data-failed', response)
                })
            },
            selectLocation(id) {
                this.$set(this.curItem, 'locationId', id)
                if (_.isUndefined(status)) return
            },
            uploadlocation(type) {
                let self = this
                return new Promise(function (resolve) {
                    resolve()
                }).then(() => {
                    if (_.isUndefined(this.curItem.locationId)) return
                    let item = _.merge({}, {
                        location_id: this.curItem.locationId,
                        building_id: this.curItem.buildingId,
                        type: type
                    })
                    let form = objectToFormData(convertKeys.toSnake(item))

                    this.run({text: 'Saving..', type: 'form'})
                    return apiQrCode.uploadLocationFiles({data: form})
                        .then(response => {
                            if (type === 'update') {
                                this.initData()
                            }
                            self.$emit('data-process-update', {
                                    running: false,
                                    success: response.data.msg
                            })
                            self.$emit('item-saved')
                        })
                        .catch(response => {
                            this.$emit('data-failed', response)
                        })
                })
            },
            updatelatlong() {
                let self = this
                return new Promise(function (resolve) {
                    resolve()
                }).then(() => {
                    let item = _.merge({}, {
                        latitude: this.curItem.latitude,
                        longitude: this.curItem.longitude,
                        files: this.latlongdata.files,
                        location_id: this.curItem.locationId,
                        notes: this.curItem.notes
                    })
                    let form = objectToFormData(convertKeys.toSnake(item))
                    this.run({text: 'Saving..', type: 'form'})
                    return apiQrCode.confirmlatlong({data: form})
                        .then(response => {
                            this.showLatLong = false
                            this.initData()
                            self.$emit('data-process-update', {
                                    running: false,
                                    success: response.data.msg
                            })
                            self.$emit('item-saved')
                        })
                        .catch(response => {
                            this.$emit('data-failed', response)
                        })
                })
            },
            watchforFileEvent() {
                if (this.$refs.fileManager) {
                    this.$refs.fileManager.$refs.uploadInput.on('fileuploaded', this.handleEventUpload)
                }
            },
            handleEventUpload(event, data, previewId, index) {
                    this.$set(this.curItem, 'files', data.response.item.fileItem)
                    this.files = data.response.item.fileItem
                    this.latlongdata.files = data.response.item.files
                    this.showLatLong = true
                    if (data.response.item.path) {
                        this.latlongdata.type = 'Geo location has been pulled from photo'
                        this.curItem.latitude = data.response.item.path.lat
                        this.curItem.longitude = data.response.item.path.lng
                    } else {
                        if (navigator.geolocation) {
                            this.latlongdata.type = 'Geo location has been pulled from browser or device , please correct if required'
                            navigator.geolocation.getCurrentPosition(
                            position => {
                                    this.$set(this.curItem, 'latitude', position.coords.latitude)
                                    this.$set(this.curItem, 'longitude', position.coords.longitude)
                            }
                            )
                        }
                    }
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>
    .customerul li {
        list-style: none;
    }
    .red {
        color: red;
    }
    .preview-holder button.kv-file-remove {
        display: none;
    }
   
</style>

<style type="text/css" lang="scss" rel="stylesheet/scss">
    .preview-holder button.kv-file-remove {
        display: none;
    }
    span.label.label-default.label-tags {
        display: none;
    }
</style>