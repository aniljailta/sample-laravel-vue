export default function(val) {
  return (val === '' || val === null) ? true : /^[a-zA-Z0-9\s#,.'-]+$/.test(val)
}
