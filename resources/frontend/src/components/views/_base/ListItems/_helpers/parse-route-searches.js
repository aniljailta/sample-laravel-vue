/*eslint new-cap: 0 */
/*eslint eqeqeq: 0 */

export default function ({ routeSearches, searches }) {
    searches = searches || {}
    let newSearches = _.cloneDeep(searches)

    // parse route searches and search applicable items
    // if found - set as selected and put the values to search-forms
    _.each(newSearches, (search) => {
        let routeSearch = _.find(routeSearches, (sItem) => !_.isUndefined(sItem[search.id]))
        if (!routeSearch) {
            search.checked = false
            return
        }

        // get value from parser if exists and return
        if (search.parse) {
            search.value = search.parse(routeSearch[search.id])
            search.checked = true
            return
        }

        search.checked = true
        search.value = routeSearch[search.id]
    })

    /*
    _.each(routeSearches, (routeSearch) => {
        let searchID = _.first(_.keys(routeSearch))
        let search = _.find(searches, {id: searchID})
        if (search) {
            search.checked = true
            search.value = routeSearch[searchID]
        }
    })
    */

    return newSearches
}
