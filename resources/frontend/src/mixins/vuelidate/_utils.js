const deepCheck = (el, callback) => {
    let selfCheck = _.some(el, callback)
    if (selfCheck) return selfCheck

    let childCheck = false
    _.each(el, (val, key) => {
        if (_.isObject(val) && key !== '$params') {
            childCheck = deepCheck(val, callback)
            if (childCheck) {
                return false
            }
        }
    })

    return childCheck
}

export default {
    deepCheck
}
