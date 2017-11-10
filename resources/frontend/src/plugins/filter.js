/*eslint no-multiple-empty-lines: "off"*/
/*eslint no-unused-vars:0 */
/*eslint no-undef:0 */

;(function () {
  let vueFilter = {}
  vueFilter.install = function (Vue, filters) {
    Vue.prototype.filters = filters
  }

  if (typeof exports === 'object') {
    module.exports = vueFilter
  } else if (typeof define === 'function' && define.amd) {
    define([], function() { return vueFilter })
  } else if (window.Vue) {
    window.VueTouch = vueFilter
    Vue.use(vueFilter)
  }
})()
