const config = require('../config')
const _ = require('./utils')
const webpack = require('webpack')
const merge = require('webpack-merge')
const baseWebpackConfig = require('./webpack.base.conf')
const ExtractTextPlugin = require('extract-text-webpack-plugin')
const os = require('os');
const UglifyJsParallelPlugin = require('webpack-uglify-parallel');
// var HtmlWebpackPlugin = require('html-webpack-plugin')
const ManifestPlugin = require("webpack-manifest-plugin"); // or HtmlWebpackPlugin

const webpackProductionConfig = merge(baseWebpackConfig, {
  bail: true,
  devtool: config.build.productionSourceMap ? '#source-map' : false,
  output: {
    path: config.build.assetsRoot,
    filename: _.assetsPath(_.addHash("[name].js", 'chunkhash')),
    chunkFilename: _.assetsPath(_.addHash("[name].js", 'chunkhash'))
  },
  plugins: [
    new webpack.LoaderOptionsPlugin({
      options: {
        context: __dirname,
        postcss: [
          // add prefix via postcss since it's faster
          require('autoprefixer') ({
            // Vue does not support ie 8 and below
            browsers: ['last 2 versions', 'ie > 8']
          })
        ],
        minimize: true,
      }
    }),
    // new webpack.NoEmitOnErrorsPlugin(), // don't generate files if error
    // http://vuejs.github.io/vue-loader/workflow/production.html
    new webpack.DefinePlugin({
      'process.env': {
        NODE_ENV: '"production"'
      }
    }),
    /*
    new webpack.optimize.UglifyJsPlugin({
      compress: {
        warnings: false,
        drop_console: true,
        unsafe: true
      },
      comments: false,
      sourceMap: false
    }),*/
    new UglifyJsParallelPlugin({
      workers: os.cpus().length, // usually having as many workers as cpu cores gives good results
      // other uglify options
      compress: {
        warnings: false,
        drop_console: false,
        unsafe: true
      },
      comments: false,
      sourceMap: false
    }),
    // extract css into its own file
    new ExtractTextPlugin({
      filename: _.addHash('[name].css', 'contenthash'), 
      allChunks: true 
    }),
    new ManifestPlugin({
      fileName: 'rev-manifest.json'
    })
  ]
})

let vueStyleLoaders = _.styleLoaders({
  sourceMap: config.build.productionSourceMap,
  extract: true,
  styleLoader: 'vue-style-loader'
}, {})

let appStyleLoaders = _.styleLoaders({
  sourceMap: config.build.productionSourceMap,
  extract: true,
  styleLoader: 'style-loader'
}, [])

let vueLoader = webpackProductionConfig.module.rules.find(function(item) {
  return item.loader === 'vue-loader'
})


vueLoader.options.loaders = merge(vueLoader.options.loaders, vueStyleLoaders)
webpackProductionConfig.module.rules = [...webpackProductionConfig.module.rules, ...appStyleLoaders]
// console.log(webpackDevConfig.module.rules)
//throw new Error(str || "Script ended by death");
module.exports = webpackProductionConfig