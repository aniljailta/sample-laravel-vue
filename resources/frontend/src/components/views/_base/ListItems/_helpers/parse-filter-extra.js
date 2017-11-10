/*eslint new-cap: 0 */

export default function ({dimensions, extra}, finalQuery) {
    let routeAlias = {}

    if (extra.orderBy) {
        let dimension = _.find(dimensions, {id: extra.orderBy})
        if (dimension && dimension.order_by) {
            finalQuery.orderBy = dimension.order_by + ' ' + extra.direction
            routeAlias.orderBy = dimension.id + ' ' + extra.direction
        }
    }

    if (extra.page) {
        finalQuery.page = extra.page
        routeAlias.page = extra.page
    }

    return {
        routeAlias,
        finalQuery
    }
}
