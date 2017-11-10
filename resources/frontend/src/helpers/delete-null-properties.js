/**
 * Delete all null (or undefined) properties from an object.
 * Set 'recurse' to true if you also want to delete properties in nested objects.
 */
function deleteNullProperties(test, recurse) {
  for (var i in test) {
    if (test[i] === null) {
      delete test[i]
    } else if (recurse && typeof test[i] === 'object') {
      deleteNullProperties(test[i], recurse)
    }
  }
}

export default deleteNullProperties
