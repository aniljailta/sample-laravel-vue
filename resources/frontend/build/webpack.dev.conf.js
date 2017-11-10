const config = require('../config')
const _ = require('./utils')
const webpack = require('webpack')
const merge = require('webpack-merge')
const baseWebpackConfig = require('./webpack.base.conf')
const ExtractTextPlugin = require('extract-text-webpack-plugin')
// const HtmlWebpackPlugin = require('html-webpack-plugin')
const ManifestPlugin = require("webpack-manifest-plugin"); // or HtmlWebpackPlugin
const BundleAnalyzerPlugin = require('webpack-bundle-analyzer').BundleAnalyzerPlugin;

const webpackDevConfig = merge(baseWebpackConfig, {
  watch: true,
  watchOptions: {
    poll: true
  },
  cache: true,
  devtool: '#inline-eval-cheap-source-map',
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
    // new webpack.NoErrorsPlugin(), // don't generate files if error
    // http://vuejs.github.io/vue-loader/workflow/production.html
    new webpack.DefinePlugin({
      'process.env': {
        NODE_ENV: '"development"'
      }
    }),
    // extract css into its own file
    new ExtractTextPlugin({ 
      filename: _.addHash('[name].css', 'contenthash'), 
      allChunks: true
    }),
    new ManifestPlugin({
      fileName: 'rev-manifest.json'
    }),
    /* new BundleAnalyzerPlugin({
      analyzerMode: 'static',
      openAnalyzer: false,
    })*/
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

let vueLoader = webpackDevConfig.module.rules.find(function(item) {
  return item.loader === 'vue-loader'
})


vueLoader.options.loaders = merge(vueLoader.options.loaders, vueStyleLoaders)
webpackDevConfig.module.rules = [...webpackDevConfig.module.rules, ...appStyleLoaders]
// console.log(webpackDevConfig.module.rules)
//throw new Error(str || "Script ended by death");
module.exports = webpackDevConfig
