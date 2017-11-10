<template>

    <div class="form-horizontal">
        <data-process v-bind:process="dataProcess" v-bind:with_loader="true"></data-process>

        <form v-if="dataIsReady">
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
                <div class="row col-xs-12">
                    <div class="col-xs-12 col-md-12">
                        <label for="name" class="control-label">Name</label>
                        <input class="form-control" placeholder="Name" name="name" type="text" id="name" v-model="curItem.name">
                    </div>
                </div>

                <div class="row col-xs-12">
                    <div class="col-xs-12 col-md-12">
                        <label for="address" class="control-label">Address</label>
                        <input class="form-control" placeholder="Address" name="address" type="text" id="address" v-model="curItem.address">
                    </div>
                </div>

                <div class="row col-xs-12">
                    <div class="col-xs-12 col-md-6">
                        <label for="country" class="control-label">Country</label>
                        <input class="form-control" placeholder="Country" name="country" type="text" id="country" v-model="curItem.country">
                    </div>

                    <div class="col-xs-12 col-md-6">
                        <label for="city" class="control-label">City</label>
                        <input class="form-control" placeholder="City" name="city" type="text" id="city" v-model="curItem.city">
                    </div>
                </div>

                <div class="row col-xs-12">
                    <div class="col-xs-12 col-md-6">
                        <label for="state" class="control-label">State</label>
                        <input class="form-control" placeholder="State" name="state" type="text" id="state" v-model="curItem.state">
                    </div>

                    <div class="col-xs-12 col-md-6">
                        <label for="zip" class="control-label">Zip</label>
                        <input class="form-control" placeholder="Zip" name="zip" type="text" id="zip" v-model="curItem.zip">
                    </div>
                </div>

                <div class="row col-xs-12">
                    <div class="col-xs-12 col-md-6">
                        <label for="categories" class="control-label">Category</label>
                        <select id="categories"
                                name="categories"
                                class="form-control"
                                v-model="curItem.category"
                                initial="off">
                            <option v-for="category in categories"
                                    v-bind:value="category.id"
                                    v-bind:selected="curItem.isActive && curItem.isActive == category.id">
                                {{ category.name }}
                            </option>
                        </select>
                    </div>

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
                    <div class="row col-xs-12 col-sm-12">
                        <div class="col-xs-12 col-md-12">
                            <label for="Notes" class="control-label">Notes</label>
                            <textarea id="Notes" type="text" class="form-control" v-model="curItem.notes"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

</template>

<script type="text/babel">
    import BaseDataItem from 'src/components/views/_base/Block/DataItem.vue'

    import objectToFormData from 'src/helpers/object-to-form-data'
    import convertKeys from 'convert-keys'

    import apiLocations from 'src/api/locations'

    export default {
        name: 'location-form',
        extends: BaseDataItem,
        data() {
            return {
                activeFlags: {},
                categories: {},
                curItem: {
                    name: null,
                    address: null,
                    country: null,
                    city: null,
                    state: null,
                    zip: null,
                    latitude: null,
                    longitude: null,
                    category: null,
                    isActive: 'yes',
                    notes: null
                }
            }
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
            save({ item, data }) {
                return apiLocations.save({ item, data }).then(response => response.data)
            },
            submit() {
                let self = this
                let item = _.merge({}, {
                    name: this.curItem.name,
                    address: this.curItem.address,
                    country: this.curItem.country,
                    city: this.curItem.city,
                    state: this.curItem.state,
                    zip: this.curItem.zip,
                    category: this.curItem.category,
                    isActive: this.curItem.isActive,
                    notes: this.curItem.notes
                })

                if (this.curItem.id) item.id = this.curItem.id

                let form = objectToFormData(convertKeys.toSnake(item))

                this.run({text: 'Saving..', type: 'form'})
                return this.save({ item: item, data: form })
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
                if (this.item.id) {
                    apiLocations.get({
                        id: this.item.id,
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
                    apiLocations.getActiveFlags({}),
                    apiLocations.categories({})
                ]

                return Promise.all(datas)
                    .then(response => {
                        this.activeFlags = response[0].data
                        this.categories = response[1].data
                        return response
                    })
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>
</style>