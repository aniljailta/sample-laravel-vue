/*eslint new-cap: 0 */

export default function (totals, finalQuery) {
    totals = _.filter(totals, total => {
        return (total.checked === true && !_.isEmpty(total.query))
    })

    let routeAlias = []
    _.each(totals, total => {
        routeAlias.push(total.id)
        _.mergeWith(finalQuery, total.query, function(objValue, srcValue) {
            if (_.isArray(objValue)) {
                return _.uniq(objValue.concat(srcValue))
            }
        })
    })
    return {
        routeAlias,
        finalQuery
    }
}
