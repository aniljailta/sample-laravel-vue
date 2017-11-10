<template>

    <div>
        <data-process v-bind:process="dataProcess" v-bind:with_loader="true"></data-process>
        <form v-if="dataIsReady" class="price_group_form">
            <div class="form-group">
                <!-- common -->
                <div class="row col-xs-12 col-sm-12">
                    <div class="col-xs-12 col-md-4">
                        <label for="name" class="control-label">Name</label>
                        <div class="form-group">
                            <input id="name" type="text" class="form-control" v-model="curItem.name">
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-4">
                        <label for="category" class="control-label">Category</label>
                        <div class="form-group">
                            <select id="category" v-model="curItem.category" class="form-control" :disabled="isEdit">
                                <option value=""></option>
                                <option value="models">Models</option>
                                <option value="options">Options</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row col-xs-12 col-sm-12">
                    <div class="col-xs-12 col-md-12">
                        <label for="notes" class="control-label">Notes</label>
                        <div class="form-group">
                            <textarea id="notes" cols="30" rows="4" class="form-control"
                                      v-model="curItem.notes"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

</template>

<script type="text/babel">
    import BaseDataItem from 'src/components/views/_base/Block/DataItem.vue'
    import apiPriceGroups from 'src/api/price-groups'
    import objectToFormData from 'src/helpers/object-to-form-data'
    import convertKeys from 'convert-keys'

    export default {
        name: 'price-group-form',
        extends: BaseDataItem,
        data() {
            return {
                // data dependency
                curItem: {
                    name: '',
                    category: ''
                },
                isEdit: false
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
            save({item, data}) {
                return apiPriceGroups.save({item, data}).then(response => response.data)
            },
            submit() {
                let self = this
                let item = {
                    id: this.curItem.id,
                    name: this.curItem.name,
                    category: this.curItem.category,
                    notes: this.curItem.notes != null ? this.curItem.notes : ''
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
                    apiPriceGroups.get({
                        id: this.id
                    })
                        .then(response => {
                            return this.initDependencies().then(() => response)
                        })
                        .then(response => {
                            let item = response.data
                            this.isEdit = true
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
                return new Promise((resolve) => {
                    resolve()
                })
            }
        }
    }
</script>