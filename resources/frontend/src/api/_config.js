export const csrfToken = function () {
  if (window.document.getElementById('_token')) {
    return window.document.getElementById('_token').content
  }
  return null
}
