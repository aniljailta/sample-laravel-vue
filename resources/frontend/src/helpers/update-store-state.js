export default function (state, data, object) {
  // mass property assignment
  if (typeof data === 'object') {
    return { ...state, ...data }
    // return Object.assign({}, state, _.cloneDeep(data))
  }

  if (typeof data === 'string') {
    let value = object
    if (object instanceof Event) {
      if (object.target.type === 'checkbox') {
        value = object.target.checked
      } else {
        value = object.target.value
      }
    }

    let obj = _.set(state, data, value)
    return { ...state, ...obj }
  }
}
