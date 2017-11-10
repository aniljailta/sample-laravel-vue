export default function(val, rule) {
  rule = (typeof rule === 'function') ? rule(this) : rule

  if (rule && _.isEmpty(val)) return false
  return true
}
