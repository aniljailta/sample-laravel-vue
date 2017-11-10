export default function(val) {
  return (val === '' || val === null) ? true : /^[a-zA-Z\s\.\-,]+$/.test(val)
}
