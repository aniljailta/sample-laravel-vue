<script type="text/babel">
    import {mapActions, mapGetters} from 'vuex'
    import Datepicker from 'components/ui/Datepicker.vue'

    export default {
        name: 'order-datepicker',
        extends: Datepicker,
        created() {
            this.$on('input', (val) => {
                this.updateOrderOrder({ date: val })
            })
        },
        computed: {
            ...mapGetters({
                orderState: 'dealerOrderForm/orderState',
                orderDate: 'dealerOrderForm/orderDate'
            }),
            dataSync() {
                return this.orderState.sync.merging
            }
        },
        methods: {
            ...mapActions({
                updateOrderOrder: 'dealerOrderForm/updateOrderOrder',
                computeCed: 'dealerOrderForm/computeCed'
            })
        },
        watch: {
            val(newVal, oldVal) {
                if (this.dataSync === 'running') return false
                this.computeCed()
            }
        }
    }
</script>