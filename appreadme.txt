# TheShedApp Structure

## Environment

- Nginx v1.10.1
- Php v7.1.7
- Nodejs v6.11.1 + npm v3.10.10

**See details in docker-usc repository** (https://bitbucket.org/uscdomination/docker-usc)

## Backend
Backend code is located in root directory (this ./) and based on Laravel 5.5 (https://laravel.com/docs/5.5/).  
**Installation:**

1. Composer:  
    - docker usage: `docker exec -u www-data -it usc_app composer install -d /app/latest`
    - non-docker usage: `composer install` from app root directory
2. import `staging_theshedapp_20171009.sql` to Mysql database (https://bitbucket.org/uscdomination/urbanshedconcepts/downloads/staging_theshedapp_20171009.sql)

_If you want to add additional package to app composer.lock - run `composer update $package_name`_ 

## Frontend
Frontend source code is located in **./resources/frontend**.  
The core is:

- ECMAScript 6 
- Vue.js v2.x (https://vuejs.org/v2/guide/)
    - Vuex (https://vuex.vuejs.org/) - global states
    - Vue-Router (https://router.vuejs.org/) - routes
    - Vuelidate (https://monterail.github.io/vuelidate/) - validation
    - Vue-Resource (https://github.com/pagekit/vue-resource) - http client
    - uiv (https://uiv.wxsm.space/) - bootstrap 3 components
- Webpack v2.x (https://webpack.js.org/configuration/)

Installation:

- 1 - `cd ./resources/frontend`
- 2 - `cp config/development.config.example config/development.config`
- 3 - `cp config/development-server.config.example config/development-server.config`
- 4 - `cp config/production.config.example config/production.config`

Docker usage:  
- 5 - `docker exec -u www-data -it usc_app bash -c "cd /app/latest/resources/frontend && npm install"`
- 6 - `docker exec -u www-data -it usc_app bash -c "cd /app/latest/resources/frontend && npm run build-dev"`

Non-docker usage:
- 5 - `npm install`
- 6 - `npm run build-dev` - frontend part will be bundled and placed to `./app/public`

_Production version should not be compiled and committed to repository_  
JS package, which can not/not needed be added via webpack should be added via bower (https://bower.io/). 

# Tenants (WIP)

## Server steps
- Add new server with host {domain}
- Generate new ssl certs by letsencrypt; add generated certs for new {domain}

## Application steps
`php artisan tenant:add {role_id} {domain} {is_active}`, where:

 - *role_id* = manufacturer | rto | super_admin
 - *domain* = xxx.manufacturer.com, usc_manufacturer.dev
 - *is_active* = 1 enabled, 0 disabled
 
 ### Work in progress
 Each new fresh tenant instance requires:
 
 - 1 - Add user to table `users` and assign new role `administrator` (generate pwd within bcrypt function http://bcrypthashgenerator.apphb.com/)
 - 2 - All settings (system -> settings) should be filled correctly after first log in.
 - 3 - Add new HelloSign API App: new client_id, domain, callback URL (**required in production**); Set `manufacturer.hellosign_client_id`
 - 4 - Add first location (`type` = plant)
 - 5 - Add first plant (first plant is default for new building/orders) + assign location
 - 6 - Add first style
 - 7 - Add first building model (based on style)
 - 8 - Add first building package category + building package (optionally)
 - 9 - Add options with category `group` roof, trim, siding
 - 10 - Add dealer (dealer/customer order form)