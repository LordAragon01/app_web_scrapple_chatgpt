const mix = require('laravel-mix');

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

/* mix.js('resources/js/app.js', 'public/js')
    .postCss('resources/css/app.css', 'public/css', [
        //
    ]); */

/*===Style Template====*/

mix.sass('resources/sass/style.scss', 'public/css');
mix.minify('public/css/style.css');

/*===Script Template====*/

mix.scripts([
    'resources/js/script.js'
], 'public/js/script.js').minify('public/js/script.js');

mix.scripts([
    'resources/js/scriptcon.js'
], 'public/js/scriptcon.js').minify('public/js/scriptcon.js');