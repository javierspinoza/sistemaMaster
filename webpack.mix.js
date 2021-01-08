let mix = require('laravel-mix');

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

mix.styles([
    'resources/assets/plantilla/css/fontawesome.min.css',
    'resources/assets/plantilla/css/simple-line-icons.min.css',
    'resources/assets/plantilla/css/vue-material.min.css',
    'resources/assets/plantilla/css/vue-multiselect.min.css',
    'resources/assets/plantilla/css/style.css',
    'resources/assets/plantilla/css/sidebar-themes.css',
    'resources/assets/plantilla/css/CustomScrollbar.min.css',
    'resources/assets/plantilla/css/dataTables.min.css',
    'resources/assets/plantilla/css/default.css'
], 'public/css/plantilla.css')
.scripts([
    'resources/assets/plantilla/js/jquery.min.js',
    'resources/assets/plantilla/js/popper.min.js',
    'resources/assets/plantilla/js/bootstrap.min.js',
    'resources/assets/plantilla/js/Chart.min.js',
    // 'resources/assets/plantilla/js/slim.min.js',
    'resources/assets/plantilla/js/pace.min.js',
    'resources/assets/plantilla/js/template.js',
    'resources/assets/plantilla/js/vue-material.min.js',
    'resources/assets/plantilla/js/vue-multiselect.min.js',
    'resources/assets/plantilla/js/jquerydataTables.min.js',
    'resources/assets/plantilla/js/dataTables.min.js',
    'resources/assets/plantilla/js/sweetalert2.all.js'
], 'public/js/plantilla.js')
.js(['resources/assets/js/app.js'],'public/js/app.js');