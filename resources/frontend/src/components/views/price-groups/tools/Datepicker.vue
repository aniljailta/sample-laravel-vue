<script type="text/babel">
    import Datepicker from 'vue-strap/src/Datepicker.vue'

    export default {
        extends: Datepicker,
        props: {
            value: {
                type: String,
                twoWay: false
            },
            disabledDatesArray: {
                default: []
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
            },
            getDateRange () {
                this.dateRange = []
                this.decadeRange = []
                const time = {
                    year: this.currDate.getFullYear(),
                    month: this.currDate.getMonth(),
                    day: this.currDate.getDate()
                }
                const yearStr = time.year.toString()
                const firstYearOfDecade = (yearStr.substring(0, yearStr.length - 1) + 0) - 1
                for (let i = 0; i < 12; i++) {
                    this.decadeRange.push({
                        text: firstYearOfDecade + i
                    })
                }

                const currMonthFirstDay = new Date(time.year, time.month, 1)
                let firstDayWeek = currMonthFirstDay.getDay() + 1
                if (firstDayWeek === 0) {
                    firstDayWeek = 7
                }
                const dayCount = this.getDayCount(time.year, time.month)
                if (firstDayWeek > 1) {
                    const preMonth = this.getYearMonth(time.year, time.month - 1)
                    const prevMonthDayCount = this.getDayCount(preMonth.year, preMonth.month)
                    for (let i = 1; i < firstDayWeek; i++) {
                        const dayText = prevMonthDayCount - firstDayWeek + i + 1
                        const date = new Date(preMonth.year, preMonth.month, dayText)
                        let sclass = 'datepicker-item-gray'
                        if (this.disabledDaysArray.indexOf(date.getDay()) > -1 || date < new Date().setHours(0, 0, 0, 0)) {
                            sclass = 'datepicker-item-disable'
                        }
                        for (let index = 0; index < this.disabledDatesArray.length; index++) {
                            if (new Date(date).setHours(0, 0, 0, 0) === new Date(this.disabledDatesArray[index]).setHours(0, 0, 0, 0)) {
                                sclass = 'datepicker-item-disable'
                            }
                        }
                        this.dateRange.push({text: dayText, date, sclass})
                    }
                }

                for (let i = 1; i <= dayCount; i++) {
                    const date = new Date(time.year, time.month, i)
                    let sclass = ''

                    if (this.disabledDaysArray.indexOf(date.getDay()) > -1 || date < new Date().setHours(0, 0, 0, 0)) {
                        sclass = 'datepicker-item-disable'
                    }
                    if (i === time.day && date.getFullYear() === time.year && date.getMonth() === time.month) {
                        sclass = 'datepicker-dateRange-item-active'
                    }
                    for (let index = 0; index < this.disabledDatesArray.length; index++) {
                        if (new Date(date).setHours(0, 0, 0, 0) === new Date(this.disabledDatesArray[index]).setHours(0, 0, 0, 0)) {
                            sclass = 'datepicker-item-disable'
                        }
                    }
                    this.dateRange.push({text: i, date, sclass})
                }

                if (this.dateRange.length < 42) {
                    const nextMonthNeed = 42 - this.dateRange.length
                    const nextMonth = this.getYearMonth(time.year, time.month + 1)

                    for (let i = 1; i <= nextMonthNeed; i++) {
                        const date = new Date(nextMonth.year, nextMonth.month, i)
                        let sclass = 'datepicker-item-gray'
                        if (this.disabledDaysArray.indexOf(date.getDay()) > -1 || date < new Date().setHours(0, 0, 0, 0)) {
                            sclass = 'datepicker-item-disable'
                        }
                        for (let index = 0; index < this.disabledDatesArray.length; index++) {
                            if (new Date(date).setHours(0, 0, 0, 0) === new Date(this.disabledDatesArray[index]).setHours(0, 0, 0, 0)) {
                                sclass = 'datepicker-item-disable'
                            }
                        }
                        this.dateRange.push({text: i, date, sclass})
                    }
                }
            }
        }
    }
</script>