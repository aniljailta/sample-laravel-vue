export default function(val) {
  return (val === '' || val === null) ? true : /^[0-9a-zA-Z]{4,9}$/.test(val)
}
