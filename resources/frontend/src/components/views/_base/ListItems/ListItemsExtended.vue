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
                        <button type="button" class="btn btn-primary btn-lg" v-on:click="openModalCreate">Add {{ entity }}</button>
                        <button type="button" class="btn btn-success btn-lg" v-on:click="search">Search</button>
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

        <modals @change-entry="changeEntry"/>
    </section>

</template>

<script type="text/babel">
    // base
    import baseListItems from 'src/components/views/_base/ListItems/ListItems.vue'
    // modals
    import crudModalsMixin from 'src/components/views/_base/ListItems/_mixins/crud-modals'
    import Modals from './Modals.vue'

    export default {
        extends: baseListItems,
        mixins: [crudModalsMixin],
        components: {
            Modals
        },
        data() {
            return {}
        },
        methods: {
            dataAllReady() {
                this.initial = false
                this.$emit('receiveList')
            }
        }
    }
</script>