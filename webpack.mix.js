let mix = require('laravel-mix')

mix
  .setPublicPath('dist')
  .js('resources/js/tool.js', 'js')
  .js('resources/js/field.js', 'js')
  .styles('resources/css/styles.css', 'dist/css/styles.css')
