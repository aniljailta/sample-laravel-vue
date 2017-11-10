var env = process.env.NODE_ENV || 'development_server'

var config = {
  development: require('./development.config'),
  development_server: require('./development-server.config'),
  production: require('./production.config')
}

module.exports = config[env]
