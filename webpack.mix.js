let mix = require('laravel-mix')

require('./nova.mix')

mix
  .setPublicPath('dist')
  .js('resources/js/cloudflare.js', 'js')
  .vue({ version: 3 })
  .nova('cloudflare')
