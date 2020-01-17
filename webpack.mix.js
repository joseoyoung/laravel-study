const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
   .js([
    './vendor/components/jquery/jquery.js',
    // '../vendor/bootstrap-sass/assets/javascripts/bootstrap.js',
    // '../vendor/fastclick/lib/fastclick.js',
    './vendor/select2/select2/dist/js/select2.js',
    './vendor/fullcalendar/fullcalendar/dist/fullcalendar.js',
   //  './vendor/dropzone/dist/dropzone.js',
    // '../vendor/tabby/jquery.textarea.js',
    // '../vendor/autosize/dist/autosize.js',
    // '../vendor/highlightjs/highlight.pack.js',
    // '../vendor/marked/lib/marked.js',
   ], 'public/js/app.js')
   .css([
      './vendor/select2/select2/dist/css/select2.css',
      './vendor/fullcalendar/fullcalendar/dist/fullcalendar.css',
   ], 'public/css')
   .sass('resources/sass/app.scss', 'public/css');
