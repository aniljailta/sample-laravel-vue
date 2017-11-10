/* eslint indent:0,key-spacing:0 */

function install (Vue) {
  Vue.component('customer-order-form', function(resolve) {
    require.ensure([], function(require) {
      let component = require('src/components/views/customer-order-form')
      resolve(component)
    })
  })
}

export default install
