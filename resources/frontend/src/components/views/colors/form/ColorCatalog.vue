<template>

    <div>
        <data-process v-bind:process="dataProcess" v-bind:with_loader="true"></data-process>

        <div class="col-md-6">
            <div v-if="dataIsReady" class="table-responsive list">
                <table class="table table-hover table-bordered table-condensed no-footer">
                    <thead>
                        <tr>
                            <th class="text-center unsortable">Selected Colors</th>
                        </tr>
                    </thead>
                    <tbody>
                    <draggable v-model="companyColors" :element="'tbody'" :options="options">
                        <tr class="text-center" v-for="color in companyColors">
                            <td>{{ color.name }} - {{ color.hex }}</td>
                        </tr>
                        <tr class="text-center" v-if="companyColors.length == 0">
                            <td>No selected Colors</td>
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
                            <th class="text-center unsortable">Available Colors</th>
                        </tr>
                    </thead>
                    <draggable v-show="companyAllowableColors.length != 0" v-model="companyAllowableColors" :element="'tbody'" :options="{group:'colors',sort:false}" v-on:end="checkMove">
                        <tr class="text-center draggable" v-for="colors in companyAllowableColors">
                            <td>{{ colors.name }} - {{ colors.hex }}</td>
                        </tr>
                    </draggable>
                    <tbody v-if="companyAllowableColors.length == 0">
                        <tr class="text-center">
                            <td>No Available Colors</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</template>

<script type="text/babel">
    import BaseDataItem from 'src/components/views/_base/Block/DataItem.vue'
    import apiColors from 'src/api/colors'
    import draggable from 'vuedraggable'

    export default {
        name: 'color-catalog',
        extends: BaseDataItem,
        data() {
            return {
                companyColors: [],
                companyAllowableColors: [],
                newColorsId: [],
                options: {
                    sort: false,
                    group: {
                        put: 'colors'
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
                    apiColors.get({
                        query: {}
                    })
                        .then(response => {
                            return this.initDependencies().then(() => {
                                return response
                            })
                        })
                        .then(response => {
                            let companyColors = response.data.data
                            this.companyCurrentColors = _.cloneDeep(companyColors)
                            this.currentCount = this.companyCurrentColors.length
                            this.companyColors = _.cloneDeep(companyColors)
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
                    apiColors.getCompanyAllowableColors()
                ]

                return Promise.all(datas)
                    .then(response => {
                        this.companyAllowableColors = $.map(response[0].data, function(value, index) { return [value] })
                        return response
                    })
            },
            checkMove(evt) {
                if (this.currentCount !== this.companyColors.length) {
                    this.currentCount = this.currentCount + 1
                    this.companyColors.length === 1 ? this.newColorsId.push(this.companyColors[0].id) : this.newColorsId.push(this.companyColors[evt.newIndex].id)
                }
            },
            save({ data }) {
                return apiColors.addColor({ data }).then(response => response.data)
            },
            submit() {
                let self = this
                this.run({text: 'Saving..', type: 'form'})
                return this.save({ data: this.newColorsId })
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