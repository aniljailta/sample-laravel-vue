<template>

    <div class="">
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
                        <label for="name" class="control-label">Name</label>
                        <input id="name" type="text" class="form-control" v-model="curItem.name">
                    </div>
                </div>
                <div class="row col-xs-12 col-sm-12">
                    <div class="col-xs-12 col-md-12">
                        <label for="description" class="control-label">Description</label>
                        <textarea id="description" type="text" class="form-control" v-model="curItem.description"></textarea>
                    </div>
                </div>
                <div class="row col-xs-12 col-sm-12">
                    <div class="col-xs-12">
                        <label class="control-label">Files</label>
                        <file-manager ref="fileManager"
                                      v-bind:options="fileManager"
                                      v-bind:storable_type="'building-package-category'"
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

    import apiBuildingPackageCategories from 'src/api/building-package-categories'

    import objectToFormData from 'src/helpers/object-to-form-data'
    import convertKeys from 'convert-keys'

    export default {
        name: 'building-package-category-form',
        extends: BaseDataItem,
        data() {
            return {
                curItem: {},
                // required models
                activeFlags: {},
                fileManager: {
                    browseOnZoneClick: false,
                    dropZoneEnabled: true,
                    showPreview: true,
                    showBrowse: true,
                    showCaption: true,
                    uploadAsync: false,
                    uploadUrl: this.item.id ? '/api/files/' : null,
                    deleteUrl: this.item.id ? '/api/files/' : null
                }
            }
        },
        components: {
            FileManager
        },
        methods: {
            save({ item, data }) {
                return apiBuildingPackageCategories.save({ item, data }).then(response => response.data)
            },
            submit() {
                let item = _.merge({}, {
                    name: this.curItem.name || null,
                    description: this.curItem.description || null,
                    isActive: this.curItem.isActive || null
                }, {
                    upload_files: this.$refs.fileManager.$refs.uploadInput[0].files
                })

                if (this.curItem.id) item.id = this.curItem.id
                let form = objectToFormData(convertKeys.toSnake(item))

                this.run({text: 'Saving..', type: 'form'})
                return this.save({ item: item, data: form })
                    .then(data => {
                        this.$emit('data-process-update', {
                            running: false,
                            success: data
                        })
                        this.$emit('item-saved')
                    })
                    .catch(response => {
                        this.$emit('data-failed', response)
                    })
            },
            initData() {
                if (this.item.id) {
                    apiBuildingPackageCategories.get({
                        id: this.item.id,
                        query: {
                            include: ['files']
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
                    this.initDependencies().then(() => {
                        this.$emit('data-ready')
                    })
                }
            },
            initDependencies() {
                const datas = []
                datas.push(apiBuildingPackageCategories.activeFlags({}))

                return Promise.all(datas)
                    .then(response => {
                        this.activeFlags = response[0].data
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
</style>