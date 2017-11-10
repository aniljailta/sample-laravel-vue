const path = require('path')
const config = require('../config')
const ExtractTextPlugin = require('extract-text-webpack-plugin')
const _ = module.exports = {}

_.assetsPath = function (_path) {
  return path.posix.join(config.build.assetsSubDirectory, _path)
}

// caching func
_.addHash = function (template, hash) {
  return config.env == 'production' ?
      template.replace(/\.[^.]+$/, `.[${hash}]$&`) : `${template}?hash=[${hash}]`;
}

_.cssLoaders = function (options) {
  options = options || {}
  // generate loader string to be used with extract text plugin or style-loader
  function generateLoaders (loaders) {
    var sourceLoader = loaders.map(function (loader) {
      var extraParamChar
      if (/\?/.test(loader)) {
        extraParamChar = '&'
      } else {
        extraParamChar = '?'
      }
      return loader + (options.sourceMap ? extraParamChar + 'sourceMap' : '')
    })

    if (options.extract) {
      // webpack v2
      return ExtractTextPlugin.extract({
        use: sourceLoader,
        fallback: options.styleLoader
      })
    } else {
      return [options.styleLoader].concat(sourceLoader)
    }
  }

  // http://vuejs.github.io/vue-loader/configurations/extract-css.html
  return {
    css: generateLoaders(['css-loader']),
    postcss: generateLoaders(['css-loader']),
    less: generateLoaders(['css-loader', 'postcss-loader', 'less-loader']),
    sass: generateLoaders(['css-loader', 'postcss-loader', 'sass-loader?indentedSyntax']),
    scss: generateLoaders(['css-loader', 'postcss-loader', 'sass-loader']),
    stylus: generateLoaders(['css-loader', 'postcss-loader', 'stylus-loader?resolve url']),
    styl: generateLoaders(['css-loader', 'postcss-loader', 'stylus-loader?resolve url'])
  }
}

// Generate loaders for standalone style files (outside of .vue)
// output can be [] or {}, depends by style-loader
_.styleLoaders = function (options, output) {
  var loaders = _.cssLoaders(options)

  for (var extension in loaders) {
    var loader = loaders[extension]
    
    // if used in root rules 
    if (options.styleLoader === 'style-loader') {
      output.push({
        test: new RegExp('\\.' + extension + '$'),
        use: loader
      })
    }

    // if used in vue sub-rules
    // vue-loader <=8.7.0 supports only string loaders with "!"
    // https://github.com/vuejs/vue-loader/issues/624#issuecomment-279167087
    if (options.styleLoader === 'vue-style-loader') {
      if (options.extract) {
        output[extension] = loader
      } else {
        output[extension] = [options.styleLoader].concat(loader).join('!') 
      }
    }
  }

  return output
}