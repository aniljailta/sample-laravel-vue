function camelCaseObjectKeys(object) {
  const returnObject = {}

  if (!_.isObject(object)) {
    return returnObject
  }

  return _.mapKeys(object, (v, k) => { return _.camelCase(k) })
}

export default camelCaseObjectKeys
