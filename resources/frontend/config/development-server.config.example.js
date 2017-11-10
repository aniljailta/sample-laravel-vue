var path = require('path')

var config = {
  env: 'development',
  build: {
    index: path.resolve(__dirname, '../dist/index.html'),
    assetsRoot: path.resolve(__dirname, '../dist'),
    assetsSubDirectory: '../static',
    assetsPublicPath: '/',
    productionSourceMap: true
  },
  dev: {
    port: 8080,
    proxyTable: {}
  },
  paths: {
    root: path.resolve(__dirname, '..')
  },
  debug: true
}

module.exports = config
