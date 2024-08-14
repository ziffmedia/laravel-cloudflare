let mix = require('laravel-mix')

require('./nova.mix')

mix
    .setPublicPath('dist')
    .js('resources/js/tool.js', 'js')
    .js('resources/js/field.js', 'js')
    .vue({ version: 3 })
    .styles('resources/css/styles.css', 'dist/css/styles.css')
    .nova('helios/redirect-uploader')
