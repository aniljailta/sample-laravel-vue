const modulesContext = require.context('./modules/', false, /\.js$/)

export default modulesContext.keys().reduce((modules, key) => {
  let newKey = key.replace(/(^\.\/)|(\.js$)/g, '')
  newKey = newKey.replace(/(\/)|(\-)/g, '_')
  modules[newKey] = modulesContext(key)
  return modules
}, {})
