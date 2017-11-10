/*eslint new-cap: 0 */

export default function ({ routeDimensions, dimensions }) {
    let newDimensions = _.cloneDeep(dimensions)

    _.each(newDimensions, (item) => {
        let exists = _.includes(routeDimensions, item.id)
        item.checked = exists
    })

    return newDimensions
}
