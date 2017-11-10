export default function(val) {
  var regex = new RegExp('^(?!219-09-9999|078-05-1120)(?!666|000|9\\d{2})\\d{3}-(?!00)\\d{2}-(?!0{4})\\d{4}$')
  return (val === '' || val === null) ? true : regex.test(val)
}
