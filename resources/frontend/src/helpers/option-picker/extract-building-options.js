export default function(buildingOptions) {
  let options = []
  _.each(buildingOptions, function(item) {
    let option = _.pick(item, ['optionId', 'quantity', 'unitPrice', 'totalPrice'])
    if (item.color) {
      option.color = _.pick(item.color, ['id', 'type', 'name'])
    }
    options.push(option)
  })
  return options
}
