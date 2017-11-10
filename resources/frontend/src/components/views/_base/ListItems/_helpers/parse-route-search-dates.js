import moment from 'moment'

const ranges = function(ranges, format) {
    let qsDateStart = _.get(ranges, 0)
    let qsDateEnd = _.get(ranges, 1)
    if (qsDateStart) ranges[0] = moment(qsDateStart).format(format)
    if (qsDateEnd) {
        if (moment(qsDateEnd).creationData().format === format) {
            ranges[1] = moment(qsDateEnd).format(format)
        } else {
            ranges[1] = moment(qsDateEnd).endOf('day').format(format)
        }
    }

    return ranges
}

const date = function(date, format) {
    if (moment(date).creationData().format === format) {
        date = moment(date).format(format)
    } else {
        date = moment(date).endOf('day').format(format)
    }
    return date
}

export default {
    ranges,
    date
}