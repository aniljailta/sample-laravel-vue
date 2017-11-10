<template>

    <div class="no-footer">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-condensed table-hover mb-none no-footer">
                <thead>
                    <tr>
                        <th class="text-left">Status</th>
                        <th class="text-left">Date</th>
                        <th class="text-center">Bill #</th>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(status, index_id) in statusHistory"
                        is="status-history-item"
                        v-bind:status="status"
                        v-bind:editable="isEditable(index_id, status)"
                        :key="index_id">
                    </tr>

                    <tr v-show="statusHistory.length == 0">
                        <td class="text-center" colspan="6">Status history is empty.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</template>

<script type="text/babel">
    import StatusHistoryItem from './StatusHistoryItem.vue'

    export default {
        data() {
            return {
            }
        },
        components: {
            StatusHistoryItem
        },
        props: {
            statusHistory: {
                default() {
                    return {}
                }
            }
        },
        computed: {
        },
        methods: {
            isEditable(index, status) {
                let size = _.size(this.statusHistory)
                let billed = false
                if (status.expense && status.expense.bill) {
                    billed = true
                }

                return (index !== 0 && index === (size - 1) && !billed)
            },
            refresh() {
                this.$parent.refresh('building-history')
            }
        }
    }
</script>

<style type="text/css" lang="scss" rel="stylesheet/scss">
</style>