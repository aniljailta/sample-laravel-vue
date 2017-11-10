/*eslint new-cap: 0 */

export default function (totals, aggregates) {
    let items = []

    _.each(aggregates, (value, key) => {
        let total = _.find(totals, { id: key })
        if (total) {
            let found = _.cloneDeep(total)
            found.value = value
            items.push(found)
        }
    })

    return items
}
