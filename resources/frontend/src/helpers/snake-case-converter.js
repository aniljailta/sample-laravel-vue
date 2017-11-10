function snakeCase(string) {
    let find = /([A-Z])/g
    let convert = function (matches) {
        return '_' + matches.toLowerCase()
    }
    return string.replace(find, convert)
}

function snakeCaseObjectKeys(object) {
    const returnObject = {}

    if (!_.isObject(object)) {
        return returnObject
    }

    return _.mapKeys(object, (v, k) => {
        return snakeCase(k)
    })
}

export default snakeCaseObjectKeys
