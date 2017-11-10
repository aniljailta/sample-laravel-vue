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
                    <div class="pull-right">
                        <button type="button" class="btn btn-primary btn-lg" v-on:click="openModalOptionCatalog">
                            Option Catalog
                        </button>
                        <button type="button" class="btn btn-primary btn-lg" v-on:click="openModalCreate">
                            Add Option
                        </button>
                        <button type="button" class="btn btn-success btn-lg" v-on:click="search">
                            Search
                        </button>
                    </div>
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

        <modal-create v-if="modal !== null && modal.mode === 'create' " :show="true"
                      v-on:close="closeModalCreate"
                      v-on:saved="fetchData"
                      :item="modal !== null && modal.mode === 'create' ? modal.item : null"></modal-create>

        <modal-option-catalog v-if="modal !== null && modal.mode === 'option-catalog' " :show="true"
                      v-on:close="closeModalOptionCatalog"
                      v-on:saved="fetchData"
                      :item="modal !== null && modal.mode === 'option-catalog' ? modal.item : null"></modal-option-catalog>

        <modal-update v-if="modal !== null && modal.mode === 'edit' " :show="true"
                      v-on:close="closeModalUpdate"
                      v-on:saved="fetchData"
                      :item="modal !== null && modal.mode === 'edit' ? modal.item : null"></modal-update>

        <modal-delete v-if="modal !== null && modal.mode === 'delete' " :show="true"
                      v-on:close="closeModalDelete"
                      v-on:removed="fetchData"
                      :item="modal !== null && modal.mode === 'delete' ? modal.item : null"></modal-delete>

    </section>

</template>

<script type="text/babel">
    // base
    import baseListItems from 'src/components/views/_base/ListItems/ListItems.vue'
    // modals
    import crudModalsMixin from 'src/components/views/_base/ListItems/_mixins/crud-modals'

    export default {
        extends: baseListItems,
        mixins: [crudModalsMixin],
        components: {},
        data() {
            return {}
        },
        methods: {
            dataAllReady() {
                this.initial = false
                this.$emit('receiveList')
            },
            openModalOptionCatalog() {
                this.$emit('change-entry', {
                    item: {},
                    mode: 'option-catalog'
                })
            },
            closeModalOptionCatalog() {
                this.$emit('change-entry', null)
            }
        }
    }
</script>