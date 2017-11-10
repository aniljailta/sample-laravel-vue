/*eslint new-cap: 0 */

export default function (searches, finalQuery, queries) {
    searches = _.filter(searches, search => {
        return (search.checked === true && !_.isEmpty(search.value))
    })

    // merge searches by several strategies
    let routeAlias = []
    _.each(searches, search => {
        let aliasItem = {}
        aliasItem[search.id] = search.value
        routeAlias.push(aliasItem)

        // TODO: not working in vue js data. Need to find a way =(
        if (queries.searches && _.isFunction(queries.searches[search.id])) {
            _.merge(finalQuery, queries.searches[search.id](search))
            return
        }

        let searchQuery = {}
        if (search.query.where && _.isString(search.query.where)) {
            searchQuery.where = {
                [search.query.where]: search.value
            }
        }

        /*
        if (search.query.where && _.isArray(search.query.where)) {
            searchQuery.where = {}
            _.set(searchQuery.where, search.query.where, search.value)
        }
        */
        _.merge(finalQuery, searchQuery)
    })
    return {
        routeAlias,
        finalQuery
    }
}
