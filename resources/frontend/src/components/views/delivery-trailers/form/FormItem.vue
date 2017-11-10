<template>

    <div>
        <data-process v-bind:process="dataProcess" v-bind:with_loader="true"></data-process>
        <form v-if="dataIsReady">
            <div class="form-group">
                <div class="row col-xs-12 col-sm-12">
                    <div class="col-xs-12 col-md-6">
                        <label for="name" class="control-label">Name</label>
                        <input id="name" placeholder="Name" type="text" class="form-control" v-model="curItem.name">
                    </div>
                    <div class="col-xs-12 col-md-6">
                        <label for="delivery_capacity" class="control-label">Delivery Capacity</label>
                        <input id="delivery_capacity" name="delivery_capacity" placeholder="Delivery Capacity" type="number" class="form-control" v-model="curItem.deliveryCapacity">
                    </div>
                    <div class="col-xs-12 col-md-12">
                        <label for="notes" class="control-label">Notes</label>
                        <textarea class="form-control" placeholder="" name="notes" id="notes" v-model="curItem.notes"></textarea>
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

    import apiTrailers from 'src/api/delivery-trailers'

    export default {
        name: 'trailer-form',
        extends: BaseDataItem,
        data() {
            return {
                curItem: {
                    name: null,
                    deliveryCapacity: null,
                    notes: null
                }
            }
        },
        components: {},
        methods: {
            save({ item, data }) {
                return apiTrailers.save({ item, data }).then(response => response.data)
            },
            submit() {
                let form = this.curItem
                form = objectToFormData(convertKeys.toSnake(form))

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
                    apiTrailers.get({
                        id: this.item.id
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

                 return Promise.all(datas)
                      .then(response => {
                           return response
                       })
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>

</style>