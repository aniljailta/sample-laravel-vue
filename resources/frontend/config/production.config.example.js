var path = require('path')

var config = {
  env: 'production',
  build: {
    index: path.resolve(__dirname, '../../../public/app/index.html'),
    assetsRoot: path.resolve(__dirname, '../../../public/app/'),
    assetsSubDirectory: './',
    assetsPublicPath: '/app/',
    productionSourceMap: true
  },
  paths: {
    root: path.resolve(__dirname, '..')
  },
  debug: false
}

module.exports = config
