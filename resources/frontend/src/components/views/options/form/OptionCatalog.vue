<template>

    <div>
        <data-process v-bind:process="dataProcess" v-bind:with_loader="true"></data-process>

        <div class="col-md-6">
            <div v-if="dataIsReady" class="table-responsive list">
                <table class="table table-hover table-bordered table-condensed no-footer">
                    <thead>
                        <tr>
                            <th class="text-center unsortable">Selected Options</th>
                        </tr>
                    </thead>
                    <draggable v-model="companyOptions" :element="'tbody'" :options="options">
                        <tr class="text-center" v-for="option in companyOptions">
                            <td>{{ option.name }} - {{ option.description }}</td>
                        </tr>
                        <tr class="text-center" v-if="companyOptions.length == 0">
                            <td>No selected Options</td>
                        </tr>
                    </draggable>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-md-6">
            <div v-if="dataIsReady" class="table-responsive list">
                <table class="table table-hover table-bordered table-condensed no-footer">
                    <thead>
                        <tr>
                            <th class="text-center unsortable">Available Options</th>
                        </tr>
                    </thead>
                    <draggable v-show="companyAllowableOptions.length != 0" v-model="companyAllowableOptions" :element="'tbody'" :options="{group:'options',sort:false}" v-on:end="checkMove">
                        <tr class="text-center draggable" v-for="options in companyAllowableOptions">
                            <td>{{ options.name }} - {{ options.description }}</td>
                        </tr>
                    </draggable>
                    <tbody v-if="companyAllowableOptions.length == 0">
                        <tr class="text-center">
                            <td>No Available Options</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</template>

<script type="text/babel">
    import BaseDataItem from 'src/components/views/_base/Block/DataItem.vue'
    import apiOptions from 'src/api/options'
    import draggable from 'vuedraggable'

    export default {
        name: 'option-catalog',
        extends: BaseDataItem,
        data() {
            return {
                companyOptions: [],
                companyAllowableOptions: [],
                newOptionsId: [],
                options: {
                    sort: false,
                    group: {
                        put: 'options'
                    }
                }
            }
        },
        components: {
            draggable
        },
        computed: {},
        methods: {
            initData() {
                    apiOptions.get({
                        query: {}
                    })
                        .then(response => {
                            return this.initDependencies().then(() => {
                                return response
                            })
                        })
                        .then(response => {
                            let companyOptions = response.data.data
                            this.companyCurrentOptions = _.cloneDeep(companyOptions)
                            this.currentCount = this.companyCurrentOptions.length
                            this.companyOptions = _.cloneDeep(companyOptions)
                        })
                        .then(() => {
                            this.$emit('data-ready')
                        })
                        .catch(response => {
                            this.$emit('data-failed', response)
                        })
            },
            initDependencies() {
                const datas = [
                    apiOptions.getCompanyAllowableOptions()
                ]

                return Promise.all(datas)
                    .then(response => {
                        this.companyAllowableOptions = $.map(response[0].data, function(value, index) { return [value] })
                        return response
                    })
            },
            checkMove(evt) {
                if (this.currentCount !== this.companyOptions.length) {
                    this.currentCount = this.currentCount + 1
                    this.companyOptions.length === 1 ? this.newOptionsId.push(this.companyOptions[0].id) : this.newOptionsId.push(this.companyOptions[evt.newIndex].id)
                }
            },
            save({ data }) {
                return apiOptions.addOption({ data }).then(response => response.data)
            },
            submit() {
                let self = this
                this.run({text: 'Saving..', type: 'form'})
                return this.save({ data: this.newOptionsId })
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
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" scoped>

</style>