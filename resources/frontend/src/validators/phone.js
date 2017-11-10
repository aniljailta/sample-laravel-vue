export default function (val, rule) {
    return (val === '' || val === null) ? true : /^[(]{0,1}[0-9]{3}[)]{0,1}[-\s\.]{0,1}[0-9]{3}[-\s\.]{0,1}[0-9]{4}$/.test(val)
}
