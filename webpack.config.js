var Encore = require('@symfony/webpack-encore');
const GlobImporter = require('node-sass-glob-importer');

Encore
    // the project directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // the public path used by the web server to access the previous directory
    .setPublicPath('/build')
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    // uncomment to create hashed filenames (e.g. app.abc123.css)
    // .enableVersioning(Encore.isProduction())

    // uncomment to define the assets of the project
    // .addEntry('js/app', './assets/js/app.js')
    // .addStyleEntry('css/app', './assets/css/app.scss')
    .addEntry('app', './assets/js/app.js')
    .addStyleEntry('simple', './vendor/schulit/common-bundle/Resources/assets/css/simple.scss')

    // uncomment if you use Sass/SCSS files
    .enableSassLoader(function(options) {
        options.importer = GlobImporter();
    })
    .enablePostCssLoader()

    // uncomment for legacy applications that require $/jQuery as a global variable
    // .autoProvidejQuery()

    .disableSingleRuntimeChunk()

    .addLoader(
        {
            test: /bootstrap\.native/,
            use: {
                loader: 'bootstrap.native-loader'
            }
        }
    )
;

module.exports = Encore.getWebpackConfig();
