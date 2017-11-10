<template>

    <div>
        <data-process v-bind:process="dataProcess" v-bind:with_loader="true"></data-process>
        <form v-if="dataIsReady" enctype="multipart/form-data">
            <div class="form-group">
                <div class="row col-xs-12 col-sm-12">
                    <div class="col-xs-12 col-md-12">
                        <label for="publishDate" class="control-label">Publish Date</label>
                        <datepicker id="publishDate" class="mr-l-10"
                                    v-bind:width="'100%'"
                                    v-bind:value="filters.moment(curItem.publishDate, 'YYYY-MM-DD HH:mm:ss', 'MM/DD/YYYY')"
                                    v-bind:format="'MM/dd/yyyy'"
                                    v-bind:disabledDatesArray="curItem.publishDates"
                                    v-bind:placeholder="'MM/DD/YYYY'"
                                    ref="publishDate"></datepicker>
                        <a class="pointer mr-l-20" v-on:click="submit('immediate')">Publish Immediately</a>
                    </div>
                </div>
            </div>
        </form>
    </div>

</template>

<script type="text/babel">
    import BaseDataItem from 'src/components/views/_base/Block/DataItem.vue'
    import Datepicker from '../tools/Datepicker.vue'
    import apiPriceGroups from 'src/api/price-groups'
    import objectToFormData from 'src/helpers/object-to-form-data'
    import convertKeys from 'convert-keys'

    export default {
        name: 'price-group-publish-form',
        extends: BaseDataItem,
        data() {
            return {
                // data dependency
                curItem: {}
            }
        },
        components: {
            Datepicker
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
                return apiPriceGroups.updatePriceGroup({item, data}).then(response => response.data)
            },
            submit(type) {
                let self = this
                let item = {
                    id: this.curItem.id,
                    category: this.curItem.category,
                    type: type
                }
                item.publishDate = this.filters.moment(this.$refs.publishDate.val, 'MM/DD/YYYY', 'YYYY-MM-DD')

                let form = objectToFormData(convertKeys.toSnake(item))

                this.run({text: 'Saving..', type: 'form'})
                return this.save({item: item, data: form})
                    .then(data => {
                        this.item.updateType = data.type
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
<style>
    .datepicker {
        padding: 0px;
    }

    .mr-l-10 { margin-left: 10px; }
    .mr-l-20 { margin-left: 20px; }
</style>