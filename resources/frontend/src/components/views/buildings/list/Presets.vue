<template>
    <div>
        <!--<div class="text-center btn-group btn-group-sm" role="group">-->
        <button class="btn btn-success btn-sm" href="buildings/#/" v-on:click.prevent="selectType('default')">
            <span class="">Default</span>
        </button>
            <button class="btn btn-success btn-sm" href="buildings/#/" v-on:click.prevent="selectType('inventoryBuildings')">
                <span class="">Inventory Buildings</span>
            </button>
            <button class="btn btn-success btn-sm" href="buildings/#/" v-on:click.prevent="selectType('productionBuildings')">
                <span class="">Buildings in Production</span>
            </button>
            <button class="btn btn-success btn-sm" href="buildings/#/" v-on:click.prevent="selectType('productionWaitingBuildings')">
                <span class="">Buildings Waiting Production</span>
            </button>
        <!--</div>-->
    </div>
</template>

<script type="text/babel">
    import Types from 'src/components/views/_base/ListItems/Types.vue'
    export default {
        extends: Types,
        data() {
            return {}
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss">
</style>