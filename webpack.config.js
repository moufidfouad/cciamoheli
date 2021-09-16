const Encore = require('@symfony/webpack-encore');

// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    // directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')
    // only needed for CDN's or sub-directory deploy
    //.setManifestKeyPrefix('build/')

    //.addStyleEntry('css/front', './assets/styles/front.scss')
    .addEntry('js/app', './assets/app.js')
    .copyFiles({
        from: './assets/vendor/mirko',
        to: 'mirko/[path][name].[ext]'
    })
    .copyFiles({
        from: './assets/vendor/blogobox',
        to: 'blogobox/[path][name].[ext]'
    })
    /*.copyFiles({
        from: './assets/addons',
        to: 'addons/[path][name].[ext]'
    })
    .copyFiles({
        from: './assets/admin',
        to: 'admin/[path][name].[ext]'
    })
    .copyFiles({
        from: './assets/vendor/pato/images',
        to: 'pato/images/[path][name].[ext]'
    })*/

    // enables the Symfony UX Stimulus bridge (used in assets/bootstrap.js)
    .enableStimulusBridge('./assets/controllers.json')
    .enableSassLoader()
    .enablePostCssLoader()
    .enableVersioning(Encore.isProduction())
    .enableSourceMaps(!Encore.isProduction())
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableVersioning(false)
    .disableSingleRuntimeChunk();
;

module.exports = Encore.getWebpackConfig();
