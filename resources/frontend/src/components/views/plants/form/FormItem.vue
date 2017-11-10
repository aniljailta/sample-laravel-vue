<template>

    <div class="form-horizontal">
        <data-process v-bind:process="dataProcess" v-bind:with_loader="true"></data-process>

        <form v-if="dataIsReady">
            <div class="form-group">
                <div class="row col-xs-12">
                    <div class="col-xs-12 col-md-12">
                        <label for="name" class="control-label">Name</label>
                        <input class="form-control" placeholder="Name" name="name" type="text" id="name" v-model="curItem.name">
                    </div>
                </div>
                <div class="row col-xs-12">
                    <div class="col-xs-12 col-md-12">
                        <label for="description" class="control-label">Description</label>
                        <textarea class="form-control" placeholder="Description" name="description" type="text" id="description" v-model="curItem.description"></textarea>
                    </div>

                    <div class="col-xs-12 col-md-6">
                        <label for="style_id" class="control-label">Location</label>
                            <select id="style_id"
                                    name="style_id"
                                    class="form-control"
                                    initial="off"
                                    v-model="curItem.locationId">
                                <option :value="null"></option>
                                <option v-for="location in locations"
                                        v-bind:value="location.id">{{ location.name }}</option>
                            </select>
                    </div>

            </div>
            </div>
        </form>
    </div>

</template>

<script type="text/babel">
    import BaseDataItem from 'src/components/views/_base/Block/DataItem.vue'
    import apiPlants from 'src/api/plants'
    import apisMixin from './apis-mixin'

    export default {
        name: 'plant-form',
        extends: BaseDataItem,
        mixins: [apisMixin],
        data() {
            return {
                curItem: {
                    id: null,
                    plantId: null,
                    name: null,
                    description: null,
                    locationId: null
                },
                locations: []
            }
        },
        components: {},
        methods: {
            save({ item, data }) {
                return apiPlants.save({ item, data }).then(response => response.data)
            },
            submit() {
                let form = this.curItem
                if (this.curItem.id) {
                   form.id = this.curItem.id
                   form._method = 'put'
                }
                this.run({text: 'Saving..', type: 'form'})
                return this.save({ item: form, data: form })
                    .then(data => {
                        this.$emit('data-process-update', {
                            running: false,
                            success: data.msg
                        })
                        this.$emit('item-saved', data.payload)
                    })
                    .catch(response => {
                        this.$emit('data-failed', response)
                    })
            },
            initData() {
                if (this.item.id) {
                    apiPlants.get({
                        id: this.item.id,
                        query: {
                            include: ['location']
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
            initDependencies(dep = null) {
                 const datas = []

                if (dep === null) {
                   datas.push(this.receiveLocations())
                }

                 return Promise.all(datas)
                      .then(response => {
                      if (dep === null) {
                           this.locations = response[0].data.data
                           }
                           return response
                       })
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>
</style>