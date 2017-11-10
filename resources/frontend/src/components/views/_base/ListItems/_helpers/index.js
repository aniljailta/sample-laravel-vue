import parseRouteDimensions from './parse-route-dimensions'
import parseRouteTotals from './parse-route-totals'
import parseRouteSearches from './parse-route-searches'
import parseRouteExtra from './parse-route-extra'

import parseFilterDimensions from './parse-filter-dimensions'
import parseFilterTotals from './parse-filter-totals'
import parseFilterSearches from './parse-filter-searches'
import parseFilterExtra from './parse-filter-extra'

import qs from 'qs'

// parse route query and return totals, dimensions for assign them to
// current schema (auto-select filter form)
const parseRoute = function ({ $router, dimensions, totals, searches, extra }) {
    let route = qs.parse($router.currentRoute.query)
    let routeTotals = route.totals || []
    let routeDimensions = route.dimensions || []
    let routeSearches = route.searches || []

    // TODO: parse searches based on search id & value
    let newTotals = parseRouteTotals({ routeTotals, totals })
    let newDimensions = parseRouteDimensions({ routeDimensions, dimensions })
    let newSearches = parseRouteSearches({ routeSearches, searches })
    let newExtra = parseRouteExtra({ route, extra })

    return {
        newTotals,
        newDimensions,
        newSearches,
        newExtra
    }
}

// parse current filter data (schema) and get final query to API
const parseFilters = function ({ dimensions, totals, searches, extra, queries, perPageCount }) {
    dimensions = dimensions || []
    totals = totals || []
    searches = searches || []
    extra = extra || {}
    queries = queries || {}

    // TODO: parse searches
    let apiQuery = {}
    let dimensionsParams = parseFilterDimensions(dimensions, apiQuery)
    let totalsParams = parseFilterTotals(totals, apiQuery)
    let searchesParams = parseFilterSearches(searches, apiQuery, queries)
    let extraParams = parseFilterExtra({dimensions, extra}, apiQuery)

    if (perPageCount !== 'System Default') {
        apiQuery.per_page = perPageCount
    }

    /*
    let routePath = qs.stringify({
        dimensions: dimensionsParams.routeAlias,
        totals: totalsParams.routeAlias,
        ...extraParams
    })
    */

    return {
        apiQuery,
        dimensionsParams,
        totalsParams,
        searchesParams,
        extraParams
    }
}

export default {
    qs,
    parseRoute,
    parseFilters
}