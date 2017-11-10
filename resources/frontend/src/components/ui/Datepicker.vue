<script type="text/babel">
    import Datepicker from 'vue-strap/src/Datepicker.vue'

    export default {
        extends: Datepicker,
        props: {
            value: {
                type: String,
                twoWay: false
            }
        },
        methods: {
            // fix dd-MM-yyyy parsing
            parse (str = this.value) {
                let date

                str = (typeof str === 'undefined' || str === null || str === false) ? '' : str
                if (str.length === 10 && (this.format === 'dd-MM-yyyy' || this.format === 'dd/MM/yyyy')) {
                    date = new Date(str.substring(6, 10), str.substring(3, 5) - 1, str.substring(0, 2))
                } else {
                    date = new Date(str)
                }
                return isNaN(date.getFullYear()) ? new Date() : date
            }
        }
    }
</script>