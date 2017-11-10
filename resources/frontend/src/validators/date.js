export default function(val) {
  return (val === '' || val === null) ? true : /^(0?[1-9]|1[012])[\/\-](0?[1-9]|[12][0-9]|3[01])[\/\-]\d{4}$/.test(val)
}
