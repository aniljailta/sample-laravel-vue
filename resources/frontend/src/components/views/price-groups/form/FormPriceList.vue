<template>

    <section class="page-list-items">
        <div class="list">
            <data-process v-bind:process="dataProcess" v-bind:with_loader="true"></data-process>

            <div class="pull-right" v-if="dataIsReady">
                <a target="_blank" :href="export_url" class="btn btn-default mr-b-10">
                    <i class="fa fa-file-excel-o" aria-hidden="true"></i> &nbsp; Export</a>
            </div>

            <div class="clearfix"><br></div>

            <div v-if="dataIsReady" class="price_list_form col-xs-12">
                <table class="table table-hover table-bordered table-condensed no-footer table-responsive">
                    <thead>
                    <tr>
                        <th class="text-center"><a>Name</a></th>
                        <th class="text-center"><a>Description</a></th>
                        <th class="text-center"><a>Price</a></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(priceList, index) in priceLists">
                        <td class="text-center" width="30%"><span>{{ priceList.name }}</span></td>
                        <td class="text-center" width="50%"><span>{{ priceList.description }}</span></td>
                        <td class="text-center" width="20%">
                            <input type="text" class="form-control input-sm" v-bind:value="priceList.priceListPrice"
                                   v-if="curItem.category == 'options'" v-model="priceList.priceListPrice">
                            <input type="text" class="form-control input-sm" v-bind:value="priceList.priceListPrice"
                                   v-if="curItem.category == 'models'" v-model="priceList.priceListPrice">
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

</template>

<script type="text/babel">
    import BaseDataItem from 'src/components/views/_base/Block/DataItem.vue'
    import apiPriceGroups from 'src/api/price-groups'
    import objectToFormData from 'src/helpers/object-to-form-data'
    import convertKeys from 'convert-keys'
    import FileManager from 'src/components/views/partials/FileManager.vue'

    export default {
        name: 'price-group-price-list-form',
        extends: BaseDataItem,
        data() {
            return {
                // data dependency
                curItem: {
                    category: this.item.category,
                    status: this.item.status
                },
                priceLists: {},
                export_url: '/api/price-group/export-price-list?id=' + this.item.id + '&category=' + this.item.category,
                fileManager: {
                    maxFileCount: 1,
                    autoReplace: true,
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
            }
        },
        methods: {
            save({item, data}) {
                return apiPriceGroups.updatePriceLists({item, data}).then(response => response.data)
            },
            submit() {
                let self = this
                let item = {
                    price_group_id: this.item.id,
                    category: this.curItem.category,
                    status: this.curItem.status,
                    priceLists: this.priceLists
                }

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
                    apiPriceGroups.getPriceLists({item: this.item})
                        .then(response => {
                            return this.initDependencies().then(() => response)
                        })
                        .then(response => {
                            let item = response.data
                            this.priceLists = _.cloneDeep(item)
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
                return new Promise((resolve) => {
                    resolve()
                })
            }
        }
    }
</script>
<style type="text/css" lang="scss" rel="stylesheet/scss">
    .file-upload-hide button.kv-file-upload {
        display: none;
    }
    .mr-b-10 { margin-bottom: 10px; }
</style>