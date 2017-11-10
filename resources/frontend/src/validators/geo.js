export default function(val) {
  return (val === '' || val === null) ? true : /^[a-zA-Z]+(?:[\s-][a-zA-Z]+)*$/.test(val)
}
