const mix = require('laravel-mix');
require('laravel-mix-purgecss');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix
    .options({
        processCssUrls: false,
        purifyCss: false
    })
    
mix.js('resources/scripts/app.js', 'public/scripts')
    .react()
    .sass('resources/styles/app.scss', 'public/styles')
    .sass('resources/styles/printable.scss', 'public/styles')
    .purgeCss()
    .copy('./node_modules/bootstrap/dist/js/bootstrap.bundle.min.js.map', 'public/scripts');

mix
    .browserSync( {
        proxy: 'pokemon.test',
        files: [ './resoures/views/**/*.php', './resources/styles/**/*.scss', './resources/scripts/**/*.js', './resources/scripts/**/*.jsx' ],
    } )