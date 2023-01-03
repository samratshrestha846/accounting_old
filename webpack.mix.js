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

mix.js('resources/js/app.js', 'public/js').vue()
    .js('resources/js/activity_session.js', 'public/js').vue()
    .postCss('resources/css/app.css', 'public/css', [
        require('postcss-import'),
        // require('tailwindcss'),
    ])
    .webpackConfig(require('./webpack.config'));

mix.styles([
        'resources/js/assets/css/pos.css',
        'resources/js/assets/css/pos_responsive.css',
    ],'public/css/pos.css')
    .version();

const path = require('path');

mix.webpackConfig({
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources/js')
        },
    },
});

if (mix.inProduction()) {
    mix.version();
}
