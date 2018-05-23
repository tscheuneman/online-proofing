let mix = require('laravel-mix');

mix.setPublicPath('src/public');

mix.js('src/resources/js/userProject.js', 'js')
    .js('src/resources/js/adminProject.js', 'js')
    .sass('src/resources/css/project.scss', 'css');
