import accounting from 'accounting'

export default function (value) {
  return accounting.formatMoney(value, '$ ', 2, ',', '.')
}
