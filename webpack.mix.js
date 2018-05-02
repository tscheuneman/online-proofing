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

mix.js('resources/assets/js/app.js', 'public/js')
   .sass('resources/assets/sass/app.scss', 'public/css')
   .js('resources/assets/js/userProject.js', 'public/js')
   .js('resources/assets/js/adminProject.js', 'public/js')
   .sass('resources/assets/sass/project.scss', 'public/css');

mix.scripts([
    'resources/assets/js/scripts/viewProject.js',
    'resources/assets/js/scripts/userActions.js',
], 'public/js/project.js');

mix.scripts([
    'resources/assets/js/scripts/adminSearch.js',
], 'public/js/search.js');


mix.disableNotifications();