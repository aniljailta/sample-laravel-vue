<template>

    <div>
        <data-process v-bind:process="dataProcess" v-bind:with_loader="true"></data-process>

        <div class="col-md-6">
            <div v-if="dataIsReady" class="table-responsive list">
                <table class="table table-hover table-bordered table-condensed no-footer">
                    <thead>
                        <tr>
                            <th class="text-center unsortable">Selected Styles</th>
                        </tr>
                    </thead>
                    <tbody>
                        <draggable v-model="companyStyles" :element="'tbody'" :options="options">
                            <tr class="text-center" v-for="style in companyStyles">
                                <td>{{ style.name }} - {{ style.description }}</td>
                            </tr>
                            <tr class="text-center" v-if="companyStyles.length == 0">
                                <td>No selected Styles</td>
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
                            <th class="text-center unsortable">Available Styles</th>
                        </tr>
                    </thead>
                    <draggable v-show="companyAllowableStyles.length != 0" v-model="companyAllowableStyles" :element="'tbody'" :options="{group:'styles',sort:false}" v-on:end="checkMove">
                        <tr class="text-center draggable" v-for="style in companyAllowableStyles">
                            <td>{{ style.name }} - {{ style.description }}</td>
                        </tr>
                    </draggable>
                    <tbody v-if="companyAllowableStyles.length == 0">
                        <tr class="text-center">
                            <td>No Available Styles</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</template>

<script type="text/babel">
    import BaseDataItem from 'src/components/views/_base/Block/DataItem.vue'
    import apiStyles from 'src/api/styles'
    import draggable from 'vuedraggable'

    export default {
        name: 'style-catalog',
        extends: BaseDataItem,
        data() {
            return {
                companyStyles: [],
                companyAllowableStyles: [],
                newStylesId: [],
                options: {
                    sort: false,
                    group: {
                        put: 'styles'
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
                    apiStyles.get({
                        query: {}
                    })
                        .then(response => {
                            return this.initDependencies().then(() => {
                                return response
                            })
                        })
                        .then(response => {
                            let companyStyles = response.data.data
                            this.companyCurrentStyles = _.cloneDeep(companyStyles)
                            this.currentCount = this.companyCurrentStyles.length
                            this.companyStyles = _.cloneDeep(companyStyles)
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
                    apiStyles.getCompanyAllowableStyles()
                ]

                return Promise.all(datas)
                    .then(response => {
                        this.companyAllowableStyles = $.map(response[0].data, function(value, index) { return [value] })
                        return response
                    })
            },
            checkMove(evt) {
                if (this.currentCount !== this.companyStyles.length) {
                    this.currentCount = this.currentCount + 1
                    this.companyStyles.length === 1 ? this.newStylesId.push(this.companyStyles[0].id) : this.newStylesId.push(this.companyStyles[evt.newIndex].id)
                }
            },
            save({ item, data }) {
                return apiStyles.save({ item, data }).then(response => response.data)
            },
            submit() {
                let self = this
                this.run({text: 'Saving..', type: 'form'})
                return this.save({ item: {}, data: this.newStylesId })
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