/* eslint indent:0,key-spacing:0 */

function install (Vue) {
  Vue.component('dealer-map', function(resolve) {
    require.ensure([], function(require) {
      let component = require('src/components/views/dealer-map')
      resolve(component)
    })
  })
}

export default install
