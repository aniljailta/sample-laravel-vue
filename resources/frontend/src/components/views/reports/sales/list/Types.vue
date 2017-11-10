<template>
    <div>
        <h4>Reports</h4>
        <!--<div class="text-center btn-group btn-group-sm" role="group">-->
            <button class="btn btn-success btn-sm" href="/reports/#!/sales" v-on:click.prevent="selectType('sales')">
                <span class="">Sales</span>
            </button>
            <button class="btn btn-success btn-sm" href="/reports/#!/sales" v-on:click.prevent="selectType('salesTax')">
                <span class="">Sales Tax</span>
            </button>
            <button class="btn btn-success btn-sm" href="/reports/#!/sales" v-on:click.prevent="selectType('cashSales')">
                <span class="">Cash Sales</span>
            </button>
            <button class="btn btn-success btn-sm" href="/reports/#!/sales" v-on:click.prevent="selectType('rtoSales')">
                <span class="">RTO Sales</span>
            </button>
            <button class="btn btn-success btn-sm" href="/reports/#!/sales" v-on:click.prevent="selectType('dealerCommission')">
                <span class="">Dealer Commission</span>
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