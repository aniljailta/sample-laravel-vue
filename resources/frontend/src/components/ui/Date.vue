<template>
    <div id="input-daterange" class="input-daterange form-control" ref="datepicker">
        <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
        <span></span>
    </div>
</template>

<script type="text/babel">
    import 'bootstrap-daterangepicker/daterangepicker.scss'
    import 'bootstrap-daterangepicker'
    import moment from 'moment'

    export default {
        data() {
            return {
            }
        },
        mounted() {
            this.renderDatepicker()
        },
        beforeDestroy() {
            this.$refs.datepicker.data('daterangepicker').remove()
        },
        components: {
        },
        props: {
            value: {
                required: true,
                default() {
                    return {}
                }
            },
            format: {
                type: String,
                default: 'MM-DD-YYYY'
            },
            minDate: {
                default() {
                    return false
                }
            },
            readOnly: {
                default() {
                    return false
                }
            }
        },
        methods: {
            renderDatepicker() {
                let options = this.datepickerOptions()
                this.$refs.datepicker = $(this.$refs.datepicker).daterangepicker(options, this.dateSelected)
                this.setPickerValue(this.value)
            },
            datepickerOptions() {
                let options = {
                    startDate: moment(),
                    endDate: moment(),
                    singleDatePicker: true,
                    minDate: this.minDate ? this.minDate : false,
                    format: 'MM-DD-YYYY',
                    showDropdowns: true
                }
                return options
            },
            dateSelected(value) {
                this.$parent.$emit('update-date', value.format(this.format))
            },
            setPickerValue(value) {
                let start = moment(value, this.format)
                this.$refs.datepicker.data('daterangepicker').setStartDate(start)
                this.$refs.datepicker.data('daterangepicker').setEndDate(start)
                $(this.$refs.datepicker).find('span').html(value)
            },
            setReadOnly(val) {
                let el = document.getElementsByClassName('show-calendar')
                el[0].style.visibility = val ? 'hidden' : 'visible'
            }
        },
        watch: {
            value: {
                deep: true,
                handler(value) {
                    this.setPickerValue(value)
                }
            },
            readOnly: {
                handler(val) {
                    this.setReadOnly(val)
                }
            }
        }
    }

</script>

<style type="text/css" lang="scss" rel="stylesheet/scss">
    .input-daterange {
        background: #fff;
        cursor: pointer;
        padding: 5px 10px;
        border: 1px solid #ccc;
        border-radius: 3px;
    }

    .daterangepicker .calendar-time select {
        border-radius: 0px;
        height: 2em;
        padding: 3px;
        outline: none;
        border: 1px solid #eee;
        border-radius: 0;
        height: initial;
    }
</style>