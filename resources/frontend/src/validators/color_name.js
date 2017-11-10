export default function(val, rule) {
  rule = (typeof rule === 'function') ? rule(this) : rule

  if (rule) {
    if (val.length <= 50) {
      return true
    }

    return false
  }

  return true
}
