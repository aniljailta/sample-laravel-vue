<template>

    <group-item v-on:close="close" v-bind:label="item.name">
        <div id="input-daterange" class="input-daterange form-control input-sm" ref="datepicker">
            <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>
            <span></span>
        </div>
    </group-item>

</template>

<script type="text/babel">
    import GroupItem from './GroupItem.vue'
    import 'bootstrap-daterangepicker/daterangepicker.scss'
    import 'bootstrap-daterangepicker'
    import moment from 'moment'

    export default {
        data() {
            return {}
        },
        components: {
            GroupItem
        },
        props: {
            item: {
                required: true,
                default() {
                    return {}
                }
            },
            format: {
                type: String,
                default: 'YYYY-MM-DD'
            }
        },
        mounted() {
            this.renderDatepicker()
        },
        beforeDestroy() {
            this.$refs.datepicker.data('daterangepicker').remove()
        },
        methods: {
            renderDatepicker() {
                let self = this
                let options = this.datepickerOptions()
                // callback when user pick the date
                let cb = function (start, end, label) {
                    self.change({
                        between: [
                            start.startOf('day').format(self.format),
                            end.endOf('day').format(self.format)
                        ]
                    })
                }

                // set inital dates
                options = this.datepickerInitialDates(options)

                // call callback for display date in local format
                cb(options.startDate, options.endDate)

                this.$refs.datepicker = $(this.$refs.datepicker).daterangepicker(options, cb)
            },
            datepickerOptions() {
                let options = {
                    format: 'MM/DD/YYYY',
                    // timeZone: 0,
                    dateLimit: false,
                    showDropdowns: true,
                    showWeekNumbers: true,
                    timePicker: false,
                    timePickerIncrement: 1,
                    timePicker12Hour: false,
                    ranges: {
                        'Today': [moment(), moment()],
                        'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                        'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                        'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                        'This Month': [moment().startOf('month'), moment().endOf('month')],
                        'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                    },
                    opens: 'right',
                    drops: 'down',
                    buttonClasses: ['btn', 'btn-sm'],
                    applyClass: 'btn-primary',
                    cancelClass: 'btn-default',
                    separator: ' to ',
                    locale: {
                        applyLabel: 'Apply',
                        cancelLabel: 'Cancel',
                        fromLabel: 'From',
                        toLabel: 'To',
                        customRangeLabel: 'Custom',
                        daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
                        monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                        firstDay: 1
                    }
                }
                return options
            },
            datepickerInitialDates(options) {
                let dateStart = moment().startOf('month')
                let dateEnd = moment().endOf('month')

                let qsDateStart = _.get(this.item.value, 'between.0')
                let qsDateEnd = _.get(this.item.value, 'between.1')
                if (qsDateStart) dateStart = moment(qsDateStart, 'YYYY-MM-DD')
                if (qsDateEnd) dateEnd = moment(qsDateEnd, 'YYYY-MM-DD')

                options.startDate = dateStart
                options.endDate = dateEnd
                return options
            },
            close() {
                let item = _.cloneDeep(this.item)
                item.checked = false
                this.$parent.$emit('update-search', item)
            },
            change(value) {
                let item = _.cloneDeep(this.item)
                item.value = value
                this.$parent.$emit('update-search', item)
            }
        },
        watch: {
            item: {
                deep: true,
                handler(item) {
                    let start = _.get(item.value, 'between.0', moment(moment().startOf('month'), 'YYYY-MM-DD'))
                    let end = _.get(item.value, 'between.1', moment(moment().endOf('month'), 'YYYY-MM-DD'))

                    let startDate = moment(start, 'YYYY-MM-DD')
                    let endDate = moment(end, 'YYYY-MM-DD')
                    this.$refs.datepicker.data('daterangepicker').setStartDate(startDate)
                    this.$refs.datepicker.data('daterangepicker').setEndDate(endDate)
                    $(this.$refs.datepicker).find('span').html(startDate.format('MM/DD/YYYY') + ' - ' + endDate.format('MM/DD/YYYY'))
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