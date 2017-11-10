var path = require('path')
var config = require('../config/index')
var _ = require('./utils')
var webpack = require('webpack')
var projectRoot = path.resolve(__dirname, '../')
var projectSrc = path.resolve(__dirname, '../src')
var ProgressBarPlugin = require('progress-bar-webpack-plugin')

require('es6-promise').polyfill()

var baseConfig = {
  performance: {
    hints: process.env.NODE_ENV === 'production' ? 'warning' : false
  },
  entry: {
    app: './src/main.js',
    dealer_order_form: './src/pages/manufacturer/dealer-order-form',
    customer_order_form: './src/pages/manufacturer/customer-order-form',
    dealer_map: './src/pages/manufacturer/dealer-map',
    wp_theme: './src/assets/wp-theme.scss',
  },
  output: {
    path: config.build.assetsRoot,
    publicPath: config.build.assetsPublicPath,
    filename: _.addHash("[name].js", 'chunkhash'),
    chunkFilename: _.addHash("[name].js", 'chunkhash')
  },
  resolve: {
    extensions: ['.js', '.vue'],
    alias: {
      src: path.resolve(__dirname, '../src'),
      assets: path.resolve(__dirname, '../src/assets'),
      components: path.resolve(__dirname, '../src/components'),
      lodash: 'lodash/lodash.min.js',
      moment: 'moment/min/moment.min.js',
      // 'vue-resourse': 'vue-resourse/dist/vue-resourse.min.js',
      'vue$': 'vue/dist/vue'
    }
  },
  resolveLoader: {
     moduleExtensions: ["-loader"]
  },
  module: {
    rules: [
      {
        enforce: "pre",
        test: /\.vue$/,
        loader: 'eslint-loader',
        include: projectSrc,
        exclude: /\/node_modules\//,
        options: {
          formatter: require('eslint-friendly-formatter'),
          failOnError: true,
        }
      },
      {
        enforce: "pre",
        test: /\.js$/,
        loader: 'eslint-loader',
        include: projectSrc,
        exclude: /\/node_modules\//,
        options: {
          formatter: require('eslint-friendly-formatter'),
          failOnError: true,
        }
      },
      {
        test: /\.vue$/,
        loader: 'vue-loader', 
        options: {
          loaders: {
            js: 'babel-loader'
          }
        }
      },
      {
        test: /\.js$/,
        loader: 'babel-loader',
        include: [
          projectSrc,
          /node_modules\/recursive-iterator\/.*/,
          /node_modules\/vue-strap\/.*/,
          /node_modules\/moment\/src\/.*/,
          /node_modules\/vue-google-maps\/src\/.*/,
        ]
        // exclude: /node_modules\/(?!(vue-strap)\/).*/,
      },
      {
        test: /\.html$/,
        loader: 'vue-html-loader'
      },
      {
        test: /\.(png|jpe?g|gif|svg|woff2?|eot|ttf|otf)(\?.*)?$/,
        loader: 'url-loader',
        options: {
          limit: 10000,
          name: _.assetsPath(_.addHash('[path][name].[ext]', 'hash:6'))
        }
      }
    ],
    noParse: [
      /vue\/dist\/.*.js/,
      // /vue-resourse\/dist\/.*.js/,
      /lodash\/lodash.min.js/,
      /moment\/min\/.*.js/
    ]
  },
  plugins: [
    new ProgressBarPlugin({
        format: '  build [:bar] ' + ':percent ' + ' (:elapsed seconds)',
        clear: false
    }),
    new webpack.ProvidePlugin({
      // moment: "moment/src/moment",
      moment: "moment",
      _: 'lodash',
      // Vue: "vue",
      // 'window.Vue': 'vue',
      // VueResource: "exports-loader?plugin!vue-resource/dist/vue-resource.es2015",
    }),
    new webpack.IgnorePlugin(/^\.\/locale$/, /moment\/src\/lib$/),
    new webpack.optimize.CommonsChunkPlugin({
      minChunks: 2,
      children: true,
      async: true
    })
  ],
  externals: {
    $: 'jQuery',
    jquery: 'jQuery',
    // lodash: '_',
    swal: 'sweetAlert',
    StripeCheckout: 'StripeCheckout'
  },
  node: {
    fs: "empty",
  }
}

module.exports = baseConfig
