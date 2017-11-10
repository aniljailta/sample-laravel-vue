export default function(val, rule) {
  rule = (typeof rule === 'function') ? rule(this) : rule
  let value = (_.isArray(val) || _.isObject(val)) ? _.first(val) : val

  if (rule) {
    if (value === 'on') {
      return true
    }
    if (value === true) {
      return true
    }
    if (value === 1) {
      return true
    }
    if (value === 'yes') {
      return true
    }

    return false
  }

  return true
}
