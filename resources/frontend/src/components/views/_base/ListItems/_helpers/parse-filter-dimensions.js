/*eslint new-cap: 0 */

export default function (dimensions, finalQuery) {
    dimensions = _.filter(dimensions, dimension => {
        return (dimension.checked === true)
    })

    let routeAlias = []
    _.each(dimensions, dimension => {
        routeAlias.push(dimension.id)
        _.mergeWith(finalQuery, dimension.query, function(objValue, srcValue) {
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
