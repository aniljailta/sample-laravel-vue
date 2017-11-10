/*eslint new-cap: 0 */

export default function ({ routeTotals, totals }) {
    let newTotals = _.cloneDeep(totals)

    _.each(newTotals, (item) => {
        let exists = _.includes(routeTotals, item.id)
        item.checked = exists
    })

    return newTotals
}
