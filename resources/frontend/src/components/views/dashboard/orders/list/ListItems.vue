<template>

    <section class="page-list-items">

        <div class="overlayable">
            <data-process v-bind:process="dataProcess" v-bind:with_loader="true"></data-process>

            <div v-show="dataIsReady">

                <types v-bind:process="dataProcess"></types>

                <div id="filters">
                    <filters ref="filters"
                             v-on:data-ready="depReady('filters')"
                             v-bind:dimensions="type.dimensions"
                             v-bind:searches="type.searches">
                    </filters>
                </div>

                <list ref="table"></list>
            </div>
        </div>

        <modal-update v-if="modal !== null && modal.mode === 'edit' " :show="true"
                      v-on:close="closeModalUpdate"
                      v-on:saved="fetchData"
                      :item="modal !== null && modal.mode === 'edit' ? modal.item : null"></modal-update>

        <modal-attachments v-if="modal !== null && modal.mode === 'attachments' " :show="true"
                           v-on:close="closeModalAttachments"
                           v-on:file-removed="fetchData"
                           :item="modal !== null && modal.mode === 'attachments' ? modal.item : null"></modal-attachments>

        <modal-dealer-order v-if="modal !== null && modal.mode === 'dealer_order' " :show="true"
                            v-on:close="closeModalDealerOrder"
                            v-on:saved="fetchData"
                            :item="modal !== null && modal.mode === 'dealer_order' ? modal.item : null"></modal-dealer-order>
    </section>

</template>

<script type="text/babel">
    // base
    import baseListItemsCrud from 'src/components/views/_base/ListItems/ListItemsCrud.vue'
    // related
    import Filters from './Filters.vue'
    import List from './table/List.vue'
    import types from './types'
    import queries from './types/queries'
    import apiOrders from 'src/api/orders'
    // modals
    import ModalAttachments from '../modals/ModalAttachments.vue'
    import ModalUpdate from '../modals/ModalUpdate.vue'
    import ModalDealerOrder from '../modals/ModalDealerOrder.vue'

    export default {
        extends: baseListItemsCrud,
        components: {
            List,
            Filters,
            ModalAttachments,
            ModalUpdate,
            ModalDealerOrder
        },
        data() {
            return {
                types: types,
                type: _.cloneDeep(types.def),
                modal: null
            }
        },
        mounted() {
            this.search()
            this.$emit('update-per-page', '1000')
        },
        methods: {
            apiGet(query) {
                query['orderBy'] === undefined ? query['orderBy'] = 'id desc' : query['orderBy']
                return apiOrders.get({ query })
            },
            queries() {
                return queries
            },
            closeModalAttachments() {
                this.$emit('change-entry', null)
            },
            closeModalDealerOrder() {
                this.$emit('change-entry', null)
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss">
    #filters {
        display: none;
    }
</style>