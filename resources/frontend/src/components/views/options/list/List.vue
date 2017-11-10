<template>

    <div class="table-responsive list">
        <data-process v-bind:with_loader="true" v-bind:process="dataProcess" :mode="'row'"></data-process>
        <list-totals v-bind:list="aggregates"></list-totals>
        <div class="pagination-wrapper">
            <list-pagination v-bind:pagination="pagination" v-bind:callback="changePage"></list-pagination>
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
            <template v-for="(items, key) in categories">
                <tbody>
                    <tr>
                        <td v-bind:colspan="countDimensions" class="text-center"><strong style="font-size:16px">{{ key }}</strong></td>
                    </tr>
                </tbody>
                <draggable :element="'tbody'" :list="categories[key]" @change="onChange" :key="key" :options="{ draggable : '.drag' }">
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
            </template>
        </table>

        <list-pagination v-bind:pagination="pagination" v-bind:callback="changePage"></list-pagination>
    </div>

</template>

<script type="text/babel">
    import DataProcessMixin from 'src/mixins/vue-data-process'
    import DataProcess from 'src/components/ui/DataProcess.vue'
    import listSortable from 'src/mixins/list-sortable'
    import parseTableTotals from 'src/components/views/_base/ListItems/_helpers/parse-table-totals'

    import ListTotals from 'src/components/views/_base/ListItems/list/ListTotals.vue'
    import ListPagination from 'src/components/views/_base/ListItems/list/ListPagination.vue'
    import ListThSortable from 'src/components/views/_base/ListItems/list/ListThSortable.vue'
    import ListTh from 'src/components/views/_base/ListItems/list/ListTh.vue'

    import apiOptions from 'src/api/options'

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
                categories: null,
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
                this.categories = response.data.data
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
            },
            onChange: function(evt) {
                let self = this
                let data = {
                    oldSortId: evt.moved.oldIndex + 1,
                    newSortId: evt.moved.newIndex + 1,
                    categoryId: evt.moved.element.categoryId
                }
                this.$emit('data-process-update', {
                    running: true
                })
                return apiOptions.updateSortId({ data })
                    .then(data => {
                        self.refreshList()
                    })
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss" src="src/assets/pages/lists.scss">
    .pagination-wrapper {
        display: inline-block;
    }

    .category {
        font-size: 20px!important;
    }
</style>