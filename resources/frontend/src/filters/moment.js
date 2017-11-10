import moment from 'moment'

export default function (value, currentFormat, newFormat) {
  if (typeof value === 'undefined' || value === null) {
    return null
  }

  return moment(value, currentFormat).format(newFormat)
}
