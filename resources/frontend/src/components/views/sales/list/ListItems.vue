<template>

    <section class="panel-featured page-list-items">
        <header class="panel-heading clearfix">
            <h2 class="panel-title">{{ title }}</h2>
        </header>

        <div class="panel-body overlayable">
            <data-process v-bind:process="dataProcess" v-bind:with_loader="true"></data-process>

            <div v-show="dataIsReady">
                <div class="list-actions__buttons">
                    <div class="clearfix"></div>
                    <button type="button" class="btn btn-success btn-lg pull-right" v-on:click="search">
                        Search
                    </button>
                </div>

                <types v-bind:process="dataProcess"></types>

                <filters ref="filters"
                         v-on:data-ready="depReady('filters')"
                         v-bind:dimensions="type.dimensions"
                         v-bind:totals="type.totals"
                         v-bind:searches="type.searches">
                </filters>

                <list ref="table"></list>
            </div>
        </div>

        <modals @change-entry="changeEntry"/>
    </section>

</template>

<script type="text/babel">
    // base
    import ListItemsExtended from 'src/components/views/_base/ListItems/ListItemsExtended.vue'
    // related
    import Filters from './Filters.vue'
    import List from './table/List.vue'
    import Modals from './Modals.vue'
    import types from './types'
    import queries from './types/queries'
    import apiSales from 'src/api/sales'

    export default {
        extends: ListItemsExtended,
        components: {
            List,
            Filters,
            Modals
        },
        data() {
            return {
                types: types,
                type: _.cloneDeep(types.def),
                modal: null
            }
        },
        methods: {
            apiGet(query) {
                return apiSales.get({ query })
            },
            queries() {
                return queries
            },
            closeModalAttachments() {
                this.$emit('change-entry', null)
            }
        }
    }
</script>