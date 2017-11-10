import accounting from 'accounting'

export default function (value) {
  if (value === null || value === '') return null
  return accounting.toFixed(value, 2)
}
