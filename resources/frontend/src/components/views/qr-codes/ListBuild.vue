<template>
    <div>
        <section class="panel-featured page-list-items">
            <header class="panel-heading clearfix" v-show="dataIsReady">
                <h2 class="panel-title">Build Status Update</h2>
            </header>

            <div class="panel-body overlayable">
                <data-process v-bind:process="dataProcess" v-bind:with_loader="true"></data-process>

                <div v-show="dataIsReady">
                    <div class="row">
                        <label class="col-sm-6 control-label">Building SN</label>
                        <span class="label label-info"> {{ building.serialNumber }}</span>
                    </div>
                    <div>
                        <div class="row">
                            <label class="col-sm-6 control-label">Current Build status</label>
                            <building-status-label :status="buildingStatus"
                                                   v-if="buildingStatus"></building-status-label>

                        </div>
                        <div class="row">
                            <label class="col-sm-6 control-label">Images Count</label>
                            <span class="label label-info"><b> {{ filescount }}</b></span>

                        </div>
                        <hr>
                        <div class="row">
                            <span class="col-xs-12 col-md-12 text-center">Changed on {{ filters.moment(lastStatus.createdAt, 'YYYY-MM-DD HH:mm:ss', 'MM/DD/YYYY HH:mm:ss') || '-'
                                }}
                                <span v-if="lastStatus.user">by {{ lastStatus.user.fullName }}</span>
                            </span>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-xs-12 col-md-12">
                                <label for="status" class="control-label">New Status</label>
                                <select id="status"
                                        name="status"
                                        class="form-control"
                                        v-model="curItem.statusId"
                                        v-on:change="selectStatus($event.target.value)"
                                        initial="off">
                                    <option v-for="status in buildingStatuses"
                                            v-bind:value="status.value"
                                            v-bind:disabled="status.options && status.options.disabled"
                                            v-bind:selected="status.value == '4'">
                                        {{ status.display }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 file-upload-hide">
                                <label class="control-label">Files</label>
                                <file-manager ref="fileManager"
                                              v-bind:options="fileManager"
                                              v-bind:storable_type="'building'"
                                              v-bind:storable_id="this.qrcode ? this.qrcode.buildingId : null"
                                              v-bind:upload_async="this.qrcode.id ? true : false"
                                              v-bind:files="curItem.files">
                                </file-manager>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 margin-top-10">
                            <button v-on:click="submit" class="btn btn-success btn-md"
                                    :disabled="validated == 1 ? true : false">Apply Status and Upload
                            </button>
                            <button v-on:click="upload" class="btn btn-warning btn-md">Upload Images Only</button>
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
                validated: 2,
                qrcode: [],
                building: [],
                buildingStatus: [],
                buildingStatuses: [],
                lastStatus: [],
                filescount: 0,
                fileManager: {
                    browseOnZoneClick: false,
                    dropZoneEnabled: true,
                    showPreview: true,
                    showUpload: false,
                    showBrowse: true,
                    showCaption: false,
                    uploadAsync: false,
                    uploadUrl: '/api/qrcodes/files'
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
        computed: {},
        methods: {
            initData() {
                this.run({text: 'Loading..'})
                this.curItem.identifier = this.identifier
                apiQrCode.getByIdentifier({
                    query: {
                        identifier: this.curItem.identifier
                    }
                }).then(response => {
                    this.qrcode = response.data.item
                    this.filescount = response.data.imagesCount
                    this.building = this.qrcode.building
                    this.lastStatus = this.qrcode.building.lastStatus
                    this.buildingStatus = this.qrcode.building.lastStatus.buildingStatus
                    this.buildingStatuses = response.data.status
                    let status = _.find(this.buildingStatuses, {priority: parseInt(this.buildingStatus.priority) + 1})
                    if (status) {
                        this.$set(this.curItem, 'statusId', status.value)
                    } else {
                        this.validated = 1
                    }
                    this.curItem.buildingId = this.qrcode.buildingId
                    this.$emit('data-process-update', {running: false})
                }).catch(response => {
                    this.$emit('data-failed', response)
                })
            },
            selectStatus(id) {
                let status = _.find(this.buildingStatuses, {value: parseInt(id)})
                this.$set(this.curItem, 'statusId', status.value)
                if (_.isUndefined(status)) return
            },
            submit() {
                if (_.isUndefined(this.curItem.statusId)) return
                let self = this
                return new Promise(function (resolve) {
                    resolve()
                }).then(() => {
                    let item = _.merge({}, {
                        building_id: this.qrcode.buildingId,
                        status_id: this.curItem.statusId,
                        identifier: this.qrcode.identifier,
                        upload_files: this.$refs.fileManager.$refs.uploadInput[0].files
                    })
                    let form = objectToFormData(convertKeys.toSnake(item))
                    this.run({text: 'Saving..', type: 'form'})
                    return apiQrCode.savestatus({item: item, data: form})
                        .then(response => {
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
            upload() {
                let self = this
                return new Promise(function (resolve) {
                    resolve()
                }).then(() => {
                    let item = _.merge({}, {
                        building_id: this.qrcode.buildingId,
                        status_id: this.curItem.statusId,
                        upload_files: this.$refs.fileManager.$refs.uploadInput[0].files
                    })
                    let form = objectToFormData(convertKeys.toSnake(item))

                    this.run({text: 'Saving..', type: 'form'})
                    return apiQrCode.uploadFiles({Identifier: this.qrcode.identifier, data: form})
                        .then(response => {
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
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss">
    .margin-top-10 {
        margin-top: 10px;
    }

    .file-upload-hide button.kv-file-upload {
        display: none;
    }
</style>
