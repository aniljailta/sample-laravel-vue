<template>

    <div class="table-responsive list">
        <data-process v-bind:with_loader="true" v-bind:process="dataProcess" :mode="'row'"></data-process>
        <list-totals v-bind:list="aggregates"></list-totals>
        <div class="pagination-wrapper">
            <list-pagination v-bind:pagination="pagination" v-bind:callback="changePage"></list-pagination>
            <div v-if="exportUrl">
                Export: <a target="_blank" :href="'/api/' + apiPath + '/export-xls?' + exportUrl"><i class="fa fa-file-excel-o" aria-hidden="true"></i> XLS</a>
                <a :href="'/api/' + apiPath + '/export-csv?' + exportUrl"><i class="fa fa-file-text-o" aria-hidden="true"></i> CSV </a>
            </div>
        </div>

        <table class="table table-hover table-bordered table-condensed no-footer" >
            <thead>
                <tr>
                    <th :is="selectedDimension.order_by ? 'list-th-sortable' : 'list-th' "
                        v-for="(selectedDimension, sd_id) in selectedDimensions" :key="sd_id"
                        v-bind:dimension="selectedDimension">
                    </th>
                </tr>
            </thead>
            <draggable v-if="drag != null" :element="'tbody'" @change="onChange" v-model="items" :options="{ draggable : '.drag' }">
                <tr is="list-item"
                    v-for="(item, item_id) in items"
                    v-if="items"
                    :dimensions="selectedDimensions"
                    :item="item"
                    :index="item_id"
                    :key="item_id">
                </tr>

                <tr v-if="items === null">
                    <td v-bind:colspan="countDimensions" class="text-center">Click "Search" to generate data</td>
                </tr>

                <tr v-if="items && !items.length && !dataProcess.error">
                    <td v-bind:colspan="countDimensions" class="text-center">No items.</td>
                </tr>
            </draggable>
            <tbody v-if="drag == null">
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

        <list-pagination v-bind:pagination="pagination" v-bind:callback="changePage"></list-pagination>
    </div>

</template>

<script type="text/babel">
    import DataProcessMixin from 'src/mixins/vue-data-process'
    import DataProcess from 'src/components/ui/DataProcess.vue'
    import listSortable from 'src/mixins/list-sortable'
    import parseTableTotals from '../_helpers/parse-table-totals'

    import ListTotals from './ListTotals.vue'
    import ListPagination from './ListPagination.vue'
    import ListThSortable from './ListThSortable.vue'
    import ListTh from './ListTh.vue'

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
                pagination: {},
                dimensions: {},
                totals: [],
                drag: null,
                items: null,
                exportUrl: ''
            }
        },
        components: {
            DataProcess,
            ListTh,
            ListThSortable,
            ListTotals,
            ListPagination
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
                this.pagination = _.pickBy(response.data, function(val, key) {
                    return _.findIndex([
                        'currentPage',
                        'from',
                        'lastPage',
                        'nextPageUrl',
                        'perPage',
                        'prevPageUrl',
                        'to',
                        'total'
                    ], key)
                })
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
    .pagination-wrapper {
        display: inline-block;
    }

</style>