export default function (val) {
  return /^(http:\/\/|https:\/\/)(.{4,})$/.test(val)
}
