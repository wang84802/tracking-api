'use strict'

var api_base_url = '52.76.175.33:20061/trackings/';
const merge = require('webpack-merge')
const prodEnv = require('./prod.env')

module.exports = merge(prodEnv, {
    NODE_ENV: '"development"',
    ROOT_API: '"http://localhost/api"'
})