/*eslint new-cap: 0 */
import qs from 'qs'

/*
 Parse and 'calculate' new route path OR refresh current list
 All request going through route
 */
export default function (store, { query = null, $router, mode }, baseQuery, action) {
    mode = mode || 'refresh'
    let { query: newQuery, baseApi } = baseQuery(query)
    let routeQuery = baseQuery($router.currentRoute.query).query
    let finalQuery

    // run action if
    // 1) new request the same as current
    // 2) mode = receive (new)
    // 2) query = null
    if (_.isEqual(routeQuery, newQuery) || mode === 'receive' || query === null) {
        finalQuery = {}
        _.merge(finalQuery, { orderBy: ['id'] }, baseApi, newQuery)

        return action(store, finalQuery)
    }

    finalQuery = qs.stringify(query)
    finalQuery = _.isEmpty(finalQuery) ? '/' : '?' + finalQuery

    $router.push({
        path: finalQuery
    })
    return
}
