const Encore = require('@symfony/webpack-encore');
const NodePolyfillPlugin = require('node-polyfill-webpack-plugin');

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')

    .addEntry('app', './assets/js/app.js')
    .addEntry('import-codes', './assets/js/import-codes.js')
    .addStyleEntry('simple', './assets/css/simple.scss')

    .configureBabel(() => {}, {
        useBuiltIns: 'usage',
        corejs: 3
    })

    .disableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())

    .enableSassLoader()
    .enablePostCssLoader()
    .enableVersioning(Encore.isProduction())

    .addPlugin(new NodePolyfillPlugin())
;

module.exports = Encore.getWebpackConfig();

module.exports.resolve.fallback = {
    fs: require.resolve('browserify-fs')
};