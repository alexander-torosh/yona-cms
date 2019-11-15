let Encore = require('@symfony/webpack-encore')

Encore
  .setOutputPath('public/build/')
  .setPublicPath('/build')
  .addEntry('front', './assets/front/webpack.index.js')
  .addEntry('dashboard', './assets/dashboard/webpack.index.js')
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