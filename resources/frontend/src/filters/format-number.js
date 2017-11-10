import accounting from 'accounting'

export default function (value, precision = 0, thousand = ',', decimal = '.') {
  if (value === null || value === '') return null
  return accounting.formatNumber(value, precision, thousand, decimal)
}
