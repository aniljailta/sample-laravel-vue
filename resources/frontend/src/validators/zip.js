export default function(val) {
  return (val === '' || val === null) ? true : /(^\d{5}$)|(^\d{5}-\d{4}$)/.test(val)
}
