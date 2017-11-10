export default function(val) {
  return (val === '' || val === null) ? true : /^[0-9A-Za-z'-]+(?:\s[0-9A-Za-z'-\,\.]+)*$/.test(val)
}
