<template>

    <div>
        <data-process v-bind:process="dataProcess" v-bind:with_loader="true"></data-process>
        <form v-if="dataIsReady">
            <div class="form-group">
                <div class="row col-xs-12">
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

                <!-- common -->
                <div class="row col-xs-12 col-sm-12">
                    <div class="col-xs-12 col-md-6">
                        <label for="name" class="control-label">Business Name</label>
                        <input id="name" type="text" class="form-control" v-model="curItem.businessName">
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <label for="style_id" class="control-label">Location</label>
                        <div class="form-inline">
                            <select id="style_id"
                                    name="style_id"
                                    class="form-control"
                                    initial="off"
                                    v-model="curItem.locationId">
                                <option :value="null"></option>
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
                </div>
                <div class="row col-xs-12 col-sm-12">
                    <div class="col-xs-4 col-sm-4">
                        <label for="phone" class="control-label">Phone</label>
                        <input id="phone" type="text" class="form-control" v-model="curItem.phone">
                    </div>
                    <div class="col-xs-4 col-sm-4">
                        <label for="email" class="control-label">Email</label>
                        <input id="email" type="text" class="form-control" v-model="curItem.email">
                    </div>
                    <div class="col-xs-4 col-sm-4">
                        <label for="ein" class="control-label">EIN</label>
                        <input id="ein" type="text" class="form-control" placeholder="NN-NNNNNNN" v-model="curItem.ein">
                    </div>
                </div>

                <div class="row col-xs-12 col-sm-12">
                    <div class="col-xs-4 col-sm-4">
                        <label class="control-label">Tax Rate</label>
                        <div class="btn-group">
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon">%</span>
                                    <input type="number"
                                           class="form-control"
                                           initial="off"
                                           v-model="curItem.taxRate"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-4 col-sm-4">
                        <label class="control-label">Commission Rate</label>
                        <div class="btn-group">
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon">%</span>
                                    <input type="number"
                                           class="form-control"
                                           initial="off"
                                           v-model="curItem.commissionRate"/>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-4 col-sm-4">
                        <label class="control-label">Deposit % for Cash Sales</label>
                        <div class="btn-group">
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon">%</span>
                                    <input type="number"
                                           class="form-control"
                                           initial="off"
                                           v-model="curItem.cashSaleDepositRate"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row col-xs-12 col-sm-12">
                    <label class="control-label col-md-12">Files</label>
                    <div class="col-xs-4 text-center">
                        <label class="control-label">Dealer Application</label>
                        <file-manager ref="dealerApp"
                                      v-bind:options="fileManager"
                                      v-bind:storable_type="'dealer'"
                                      v-bind:storable_id="curItem.id ? curItem.id : null"
                                      v-bind:category_id="'dealer_application'"
                                      v-bind:upload_async="curItem.id ? true : false"
                                      v-bind:files="sortFiles(curItem.files, 'dealer_application')">
                        </file-manager>
                    </div>

                    <div class="col-xs-4 text-center">
                        <label class="control-label">W9</label>
                        <file-manager ref="w9"
                                      v-bind:options="fileManager"
                                      v-bind:storable_type="'dealer'"
                                      v-bind:storable_id="curItem.id ? curItem.id : null"
                                      v-bind:category_id="'w9'"
                                      v-bind:upload_async="curItem.id ? true : false"
                                      v-bind:files="sortFiles(curItem.files, 'w9')">
                        </file-manager>
                    </div>

                    <div class="col-xs-4 text-center">
                        <label class="control-label">Other</label>
                        <file-manager ref="other"
                                      v-bind:options="fileManager"
                                      v-bind:storable_type="'dealer'"
                                      v-bind:storable_id="curItem.id ? curItem.id : null"
                                      v-bind:category_id="'other'"
                                      v-bind:upload_async="curItem.id ? true : false"
                                      v-bind:files="sortFiles(curItem.files, 'other')">
                        </file-manager>
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
    import apiDealers from 'src/api/dealers'
    import apisMixin from './apis-mixin'
    import FileManager from 'src/components/views/partials/FileManager.vue'

    import objectToFormData from 'src/helpers/object-to-form-data'
    import convertKeys from 'convert-keys'

    export default {
        name: 'dealer-model-form',
        extends: BaseDataItem,
        mixins: [apisMixin],
        data() {
            return {
                createLocationModal: false,
                curItem: {},
                locations: [],
                activeFlags: {},
                fileManager: {
                    browseOnZoneClick: false,
                    dropZoneEnabled: true,
                    showPreview: true,
                    showBrowse: true,
                    showCaption: true,
                    uploadAsync: false,
                    uploadUrl: '/api/dealer/' + this.item.id + '/files/',
                    deleteUrl: '/api/dealer/' + this.item.id + '/files/'
                }
            }
        },
        components: {
            FileManager,
            LocationModalCreate: function(resolve) {
                require(['../../locations/modals/ModalCreate.vue'], resolve)
            }
        },
        methods: {
            save({ item, data }) {
                return apiDealers.save({ item, data }).then(response => response.data)
            },
            submit() {
                let self = this
                let item = _.merge({}, {
                    businessName: this.curItem.businessName || null,
                    phone: this.curItem.phone || null,
                    email: this.curItem.email || null,
                    ein: this.curItem.ein || null,
                    locationId: this.curItem.locationId || null,
                    taxRate: this.curItem.taxRate || null,
                    commissionRate: this.curItem.commissionRate || null,
                    cashSaleDepositRate: this.curItem.cashSaleDepositRate || null,
                    isActive: this.curItem.isActive || null
                }, {
                    dealerAppFiles: this.$refs.dealerApp.$refs.uploadInput[0].files,
                    w9Files: this.$refs.w9.$refs.uploadInput[0].files,
                    otherFiles: this.$refs.other.$refs.uploadInput[0].files
                })

                if (this.curItem.id) {
                    item.id = this.curItem.id
                    item._method = 'put'
                }

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
            openCreateLocationModal() {
                this.createLocationModal = true
            },
            closeCreateLocationModal() {
                this.createLocationModal = false
            },
            pickNewLocation(newLocation) {
                return new Promise(this.receiveLocations).then(resolve => {
                    this.$set(this.curItem, 'locationEndId', newLocation.id)
                    resolve()
                }).catch(response => {
                    this.$emit('data-failed', response)
                })
            },
            initData() {
                if (this.item.id) {
                    apiDealers.get({
                        id: this.item.id,
                        query: {
                            include: [
                                'location',
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
                    datas.push(this.receiveLocations())
                }

                return Promise.all(datas)
                    .then(response => {
                        if (dep === null) {
                            this.activeFlags = response[0].data
                            this.locations = response[1].data.data
                        }

                        return response
                    })
            },
            sortFiles(files, type) {
                let arr = []
                $.each(files, function (key, value) {
                    if (value.categoryId === type) {
                        arr.push(value)
                    }
                })
                return arr
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>
    .row {
        margin-bottom: 0.5em;
    }
</style>