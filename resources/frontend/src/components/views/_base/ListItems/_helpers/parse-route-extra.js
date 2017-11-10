/*eslint new-cap: 0 */

export default function ({ route, extra }) {
    extra = extra || {}
    let newExtra = _.cloneDeep(extra)

    // reset page and sort (but leave potential params still existed)
    newExtra.orderBy = null
    newExtra.page = null

    if (route.orderBy) {
        let order = _.zipObject(['field', 'direction'], route.orderBy.split(' '))
        if (order.field) newExtra.orderBy = order.field
        if (order.direction) newExtra.direction = order.direction
    }

    if (route.page) newExtra.page = route.page
    return newExtra
}
