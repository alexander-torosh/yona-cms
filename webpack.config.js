let Encore = require('@symfony/webpack-encore')

Encore
  .setOutputPath('public/build/')
  .setPublicPath('/build')
  .addEntry('app', './frontend/js/index.js')
  .enableSingleRuntimeChunk()
  .cleanupOutputBeforeBuild()

  // Sass
  .enableSassLoader()
  // React
  .enableReactPreset()

  // Maps
  .enableSourceMaps(!Encore.isProduction())
  // Versioning
  .enableVersioning(Encore.isProduction())

module.exports = Encore.getWebpackConfig();