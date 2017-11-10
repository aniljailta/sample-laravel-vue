/* eslint indent:0,key-spacing:0 */

let filters = {
  'moment':             './moment.js',
  'ucfirst':            './ucfirst.js',
  'money':              './money.js',
  'currency':           './currency.js',
  'formatNumber':       './format-number.js'
}

function install (Vue) {
  // This lets us do dynamic requires
  let req = require.context('./', true, /^\.\/.*\.js$/)

  // Attach each filter in Vue
  for (let filter in filters) {
    Vue.filter(filter, req(filters[filter]))
  }
}

export default install
