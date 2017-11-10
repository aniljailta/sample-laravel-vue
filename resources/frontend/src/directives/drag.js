/*eslint no-multiple-empty-lines: "off"*/
/*eslint no-unused-vars:0 */
/*eslint no-undef:0 */

let setDragging = function(el, sel) {
  var startX, startY, initialMouseX, initialMouseY

  function mouseMove(e) {
    var dx = e.clientX - initialMouseX
    var dy = e.clientY - initialMouseY
    sel.style.top = startY + dy + 'px'
    sel.style.left = startX + dx + 'px'
    return false
  }

  function mouseup(e) {
    document.removeEventListener('mousemove', mouseMove)
    document.removeEventListener('mouseup', mouseup)
  }

  el.addEventListener('mousedown', function (e) {
    startX = sel.offsetLeft
    startY = sel.offsetTop
    initialMouseX = e.clientX
    initialMouseY = e.clientY

    document.addEventListener('mousemove', mouseMove)
    document.addEventListener('mouseup', mouseup)
    return false
  }, false)


  function touchMove(e) {
    var dx = e.touches[0].clientX - initialMouseX
    var dy = e.touches[0].clientY - initialMouseY
    sel.style.top = startY + dy + 'px'
    sel.style.left = startX + dx + 'px'
    return false
  }

  function touchEnd(e) {
    document.removeEventListener('touchmove', touchMove)
    document.removeEventListener('touchend', touchEnd)
    document.removeEventListener('touchcancel', touchEnd)
  }

  el.addEventListener('touchstart', function (e) {
    startX = sel.offsetLeft
    startY = sel.offsetTop
    initialMouseX = e.touches[0].clientX
    initialMouseY = e.touches[0].clientY

    document.addEventListener('touchmove', touchMove)
    document.addEventListener('touchend', touchEnd)
    document.addEventListener('touchcancel', touchEnd)

    return false
  })
}

const install = function (Vue) {
  Vue.directive('drag', {
    bind(el) {
      el = this.el // vue v1. Need to remove this line for vue v2
      // el.style.position = 'relative'
      var sels

      if (this.arg) {
        sels = this.el.querySelectorAll('.' + this.arg)
        // sels = $(this.el).find('.' + this.arg)
      }

      if (!sels) sels = [el]

      _.each(sels, function (item) {
        setDragging(item, el)
      })
    },
    update: function (newValue, oldValue) {}
  })
}

export default install
