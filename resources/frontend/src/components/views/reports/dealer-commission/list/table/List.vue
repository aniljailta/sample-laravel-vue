<template>
    <table class="table table-hover table-bordered table-condensed no-footer">
        <thead>
            <tr>
                <th class="text-center unsortable">Order ID</th>
                <th class="text-center unsortable">Date Order Submitted</th>
                <th class="text-center unsortable">Date Order Updated</th>
                <th class="text-center unsortable">Order Status</th>
                <th class="text-center unsortable">Dealer</th>
                <th class="text-center unsortable">Customer Name</th>
                <th class="text-center unsortable">Building Serial Number</th>
                <th class="text-center unsortable">Total Building Price</th>
                <th class="text-center unsortable">Commission Rate</th>
                <th class="text-center unsortable">Commission Amount</th>
                <th class="text-center unsortable">Dealer Discount</th>
                <th class="text-center unsortable">Amount Due</th>
            </tr>
        </thead>
        <tbody>
            <tr is="list-item"
                v-for="(commissionData, i) in currentListData"
                :index="i"
                :commission-data="commissionData" @update:commission-data="updateElement"
                :is-modify-enabled="isModifyEnabled"/>
        </tbody>
    </table>
</template>
<script type="text/babel">
    import ListItem from './ListItem.vue'

    export default {
        components: {
            ListItem
        },
        data() {
            return {}
        },
        props: {
            isModifyEnabled: {
                type: Boolean,
                default: false
            },
            currentListData: {
                type: [Array, Object],
                default() {
                    return []
                }
            }
        },
        mounted() {},
        created() {},
        methods: {
            updateElement(element) {
                let newList = _.cloneDeep(this.currentListData)
                let index = _.findIndex(newList, {id: parseInt(element.id)})
                let oldElement = newList[index]
                let newElement = {...oldElement, ...element}

                newList.splice(index, 1, newElement)
                this.$emit('update:current-list-data', newList)
            }
        },
        computed: {}
    }
</script>
<style type="text/css" lang="scss" rel="stylesheet/scss">

</style>