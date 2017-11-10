<template>

    <section class="panel-featured page-list-items">
        <header class="panel-heading clearfix">
            <h2 class="panel-title">{{ title }}</h2>
        </header>

        <div class="panel-body overlayable">
            <data-process v-bind:process="dataProcess" v-bind:with_loader="true"/>

            <div v-show="dataIsReady">
                <div class="list-actions__buttons">
                    <div class="clearfix"></div>
                    <div class="pull-right">
                        <button type="button" class="btn btn-primary btn-lg" v-on:click="openModalCreate">Add {{ entity }}</button>
                        <button type="button" class="btn btn-default btn-lg" v-on:click="openModalImport">Import</button>
                        <button type="button" class="btn btn-success btn-lg" v-on:click="search">Search</button>
                    </div>
                </div>

                <types v-bind:process="dataProcess"/>

                <filters ref="filters"
                         v-on:data-ready="depReady('filters')"
                         v-bind:dimensions="type.dimensions"
                         v-bind:totals="type.totals"
                         v-bind:searches="type.searches"/>

                <list ref="table"/>
            </div>
        </div>

        <modals @change-entry="changeEntry"/>
    </section>

</template>

<script type="text/babel">
    // base
    import baseListItemsExtended from 'src/components/views/_base/ListItems/ListItemsExtended.vue'
    // related
    import Filters from './Filters.vue'
    import Modals from './Modals.vue'
    import List from './table/List.vue'
    import types from './types'
    import queries from './types/queries'
    import apiPriceGroups from 'src/api/price-groups'
    import snakeCaseObjectKeys from 'src/helpers/snake-case-converter'

    export default {
        extends: baseListItemsExtended,
        components: {
            List,
            Filters,
            Modals
        },
        data() {
            return {
                entity: 'Price Group',
                types: types,
                type: _.cloneDeep(types.def),
                exportUrl: '',
                apiPath: 'price-group',
                modal: null
            }
        },
        methods: {
            apiGet(query) {
                let currentQuery = this.$url(apiPriceGroups.actions.get.url, snakeCaseObjectKeys(query))
                this.exportUrl = this.getQueryParamsOnly(currentQuery)

                return apiPriceGroups.get({ query })
            },
            queries() {
                return queries
            },
            getQueryParamsOnly(currentQuery) {
                return currentQuery.substring(currentQuery.indexOf('?') + 1)
            },
            openModalImport() {
                this.$emit('change-entry', {
                    item: {},
                    mode: 'import'
                })
            }
        }
    }
</script>