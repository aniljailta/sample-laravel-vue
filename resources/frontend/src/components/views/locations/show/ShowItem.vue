<template>
    <section class="panel-featured">
        <header class="panel-heading panel-with-actions" v-if="curItem.id">
            <h2 class="panel-title">
                <span>Location # {{ curItem.id }}</span>
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
                                    <dt>Name</dt>
                                    <dd><span class="label label-info">{{ curItem.name || '-' }}</span></dd>

                                    <dt>Address</dt>
                                    <dd>
                                        {{ curItem.address }}
                                        {{ curItem.city }}
                                        {{ curItem.state }}
                                        {{ curItem.country }} - {{ curItem.zip }}

                                    </dd>

                                    <dt>Is Active</dt>
                                    <dd>
                                        <span class="label label-success" v-if="curItem.isActive === 'yes' ">Yes</span>
                                        <span class="label label-danger" v-if="curItem.isActive === 'no' ">No</span>
                                    </dd>

                                    <dt>Category</dt>
                                    <dd> <span class="label label-default">{{ curItem.category || '-' }}</span></dd>

                                    <dt>Latitude</dt>
                                    <dd>{{ curItem.latitude || '-' }}</dd>

                                    <dt>Longitude</dt>
                                    <dd>{{ curItem.longitude || '-' }}</dd>
                                </dl>
                            </div>
                        </section>

                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6">
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
                <!-- Files verifed -->
                <section class="panel sub-panel">
                    <header class="panel-heading">
                        <h4 class="panel-title">Files</h4>
                    </header>
                    <div class="panel-body">
                        <files-option :files="files"></files-option>
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
                                      v-bind:storable_type="'location'"
                                      v-bind:storable_id="curItem.id ? curItem.id : null"
                                      v-bind:files="files">
                        </file-manager>
                    </div>
                </section>
            </template>
        </div>
    </section>

</template>

<script type="text/babel">
    import BaseDataItem from 'src/components/views/_base/Block/DataItem.vue'
    import FileManager from 'src/components/views/partials/FileManager.vue'
    import FilesOption from '../file-options/FileOptions.vue'
    import apiLocations from 'src/api/locations'

    export default {
        name: 'building-show',
        extends: BaseDataItem,
        data() {
            return {
                id: null,
                modal: null,
                curItem: {},
                files: {},
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
            FilesOption
        },
        methods: {
            initData() {
                this.run({text: 'Loading..'})
                this.curItem.id = this.$route.params.id
                if (this.curItem.id) {
                    apiLocations.get({
                        id: this.curItem.id,
                        query: {}
                    })
                        .then(response => {
                            return this.initDependencies().then(() => {
                                return response
                            })
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
            initDependencies() {
                const datas = [
                    apiLocations.getFiles({
                        query: {
                            locationId: this.curItem.id
                        }
                    })
                ]

                return Promise.all(datas)
                    .then(response => {
                        this.files = response[0].data
                        this.$set(this.curItem, 'files', response[0].data)
                        return response
                    })
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