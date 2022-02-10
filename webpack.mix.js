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
    .sass('resources/styles/app.scss', 'public/styles')
    .purgeCss();

mix
    .browserSync( {
        proxy: 'pokemon.test',
        //files: [ './resoures/views/**/*.php', './public/styles/**/*.css', './public/scripts/**/*.js' ],
    } )