<template>

    <div class="table-responsive list">
        <data-process v-bind:with_loader="true" v-bind:process="dataProcess" :mode="'row'"></data-process>
        <list-totals v-bind:list="aggregates"></list-totals>
        <table class="table table-hover table-bordered table-condensed no-footer" >
            <thead>
                <tr>
                    <th :is="selectedDimension.order_by ? 'list-th-sortable' : 'list-th' "
                        v-for="(selectedDimension, sd_id) in selectedDimensions" :key="sd_id"
                        v-bind:dimension="selectedDimension">
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr is="list-item"
                    v-for="(item, item_id) in items"
                    v-if="items"
                    v-bind:dimensions="selectedDimensions"
                    v-bind:item="item"
                    v-bind:index="item_id"
                    :key="item_id">
                </tr>

                <tr v-if="items === null">
                    <td v-bind:colspan="countDimensions" class="text-center">Click "Search" to generate data</td>
                </tr>

                <tr v-if="items && !items.length && !dataProcess.error">
                    <td v-bind:colspan="countDimensions" class="text-center">No items.</td>
                </tr>
            </tbody>
        </table>
    </div>

</template>

<script type="text/babel">
    import DataProcessMixin from 'src/mixins/vue-data-process'
    import DataProcess from 'src/components/ui/DataProcess.vue'
    import listSortable from 'src/mixins/list-sortable'
    import parseTableTotals from 'src/components/views/_base/ListItems/_helpers/parse-table-totals'

    import ListTotals from 'src/components/views/_base/ListItems/list/ListTotals.vue'
    import ListThSortable from 'src/components/views/_base/ListItems/list/ListThSortable.vue'
    import ListTh from 'src/components/views/_base/ListItems/list/ListTh.vue'

    export default {
        mixins: [listSortable, DataProcessMixin],
        data() {
            return {
                dataProcess: {
                    type: 'data',
                    text: null,
                    running: false,
                    error: null,
                    success: null
                },
                aggregates: {},
                dimensions: {},
                totals: [],
                items: null,
                exportUrl: ''
            }
        },
        components: {
            DataProcess,
            ListTh,
            ListThSortable,
            ListTotals
        },
        created() {
            this.$emit('data-ready')
            this.apiPath = this.$parent.apiPath
        },
        beforeUpdate() {
            this.exportUrl = this.$parent.exportUrl
        },
        computed: {
            countDimensions() {
                return _.size(_.filter(this.dimensions, { 'checked': true }))
            },
            selectedDimensions() {
                let dimensions = _.filter(_.cloneDeep(this.dimensions), { checked: true })
                return _.keyBy(dimensions, 'id')
            }
        },
        methods: {
            setResponse(response) {
                this.items = response.data.data
                // apply aggregates (get current totals items (from filters) and apply to list)
                this.aggregates = parseTableTotals(this.totals, response.data.aggregates)
                // apply dimensions (current dimensions from filter)
                this.$emit('data-ready')
            },
            refreshList(extraParams) {
                this.$parent.$emit('update-extra', extraParams)
                this.$parent.$emit('apply-filters-to-route')
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" src="src/assets/pages/lists.scss">
</style>