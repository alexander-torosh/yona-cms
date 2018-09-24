/**
 * WebPack configuration file
 */

const path = require('path')
const fs = require('fs')
const autoprefixer = require('autoprefixer')
const MiniCssExtractPlugin = require('mini-css-extract-plugin')

const env = process.env.NODE_ENV || 'production'

const MODULES_PATH = path.resolve(__dirname, 'src', 'modules')
const DIST_PATH = path.resolve(__dirname, 'public', 'dist')

const uncamelize = (str, separator) => {
  // Assume default separator is a single space.
  if (typeof(separator) === 'undefined') {
    separator = ' '
  }
  // Replace all capital letters by separator followed by lowercase one
  str = str.replace(/[A-Z]/g, function (letter) {
    return separator + letter.toLowerCase()
  })
  // Remove first separator
  return str.replace('/^' + separator + '/', '')
}

const presetEnv = ['@babel/preset-env', {
  modules: false,
  loose: true,
}]

const pluginTransformRuntime = [
  '@babel/plugin-transform-runtime', {
    corejs: 2,
    helpers: true,
    regenerator: true,
    useESModules: false,
  },
]

// Dynamic modules loading
const modulesSources = {}
const modulesFile = path.resolve(__dirname, 'src', 'app', 'config', 'modules.json')

const modulesContents = fs.readFileSync(modulesFile)
if (modulesContents.length > 0) {

  const modules = JSON.parse(modulesContents)
  if (modules.length > 0) {

    Object.values(modules).forEach((module) => {

      const moduleJsFile = path.resolve(MODULES_PATH, module, 'assets', 'js', 'index.js')
      if (fs.existsSync(moduleJsFile)) {

        modulesSources[uncamelize(module)] = moduleJsFile
      }

      const moduleScssFile = path.resolve(MODULES_PATH, module, 'assets', 'scss', 'index.scss')
      if (fs.existsSync(moduleScssFile)) {

        modulesSources[uncamelize(module) + '-styles'] = moduleScssFile
      }
    })

    console.log(modulesSources)
  }
}

module.exports = {
  /* entry: {
   index: path.join(APP_PATH, 'js', 'index.js'),
   style: path.join(APP_PATH, 'scss', 'index.scss'),
   }, */
  entry: modulesSources,
  output: {
    path: DIST_PATH,
    chunkFilename: `[name]${env === 'production' && '.[chunkhash]'}.bundle.js`,
    filename: '[name].js',
    publicPath: '/dist/',
  },
  module: {
    rules: [
      {
        test: /\.js$/,
        exclude: /(node_modules)/,
        use: {
          loader: 'babel-loader',
          options: {
            presets: [presetEnv, '@babel/preset-flow'],
            plugins: [
              pluginTransformRuntime,
              '@babel/plugin-proposal-object-rest-spread',
              '@babel/plugin-syntax-dynamic-import',
            ],
          },
        },
      },
      {
        test: /\.jsx$/,
        exclude: /(node_modules|bower_components)/, // (node_modules|bower_components)
        use: {
          loader: 'babel-loader',
          options: {
            presets: [
              presetEnv,
              '@babel/preset-react',
              '@babel/preset-flow',
            ],
            plugins: [
              pluginTransformRuntime,
              '@babel/plugin-proposal-object-rest-spread',
              '@babel/plugin-syntax-dynamic-import',
            ],
          },
        },
      },
      {
        test: /\.(scss|css)$/,
        include: MODULES_PATH,
        use: [
          MiniCssExtractPlugin.loader,
          {
            loader: 'css-loader',
            options: {},
          },
          {
            loader: 'postcss-loader',
            options: {
              ident: 'postcss',
              plugins: () => [
                autoprefixer(),
              ],
            },
          },
          {
            loader: 'sass-loader',
            options: {},
          },
        ],
      },
      {
        test: /\.(woff|woff2).*$/,
        use: [
          {
            loader: 'url-loader',
            options: {
              limit: 8192,
              mimetype: 'application/font-woff',
              name: '[name].[ext]',
              outputPath: 'fonts',
            },
          },
        ],
      },
      {
        test: /\.ttf.*$/,
        use: [
          {
            loader: 'url-loader',
            options: {
              limit: 8192,
              mimetype: 'application/octet-stream',
              name: '[name].[ext]',
              outputPath: 'fonts',
            },
          },
        ],
      },
      {
        test: /\.svg.*$/,
        use: [
          {
            loader: 'file-loader',
            options: {
              limit: 8192,
              mimetype: 'image/svg+xml',
              name: '[name].[ext]',
              outputPath: 'svg',
            },
          },
        ],
      },
      {
        test: /\.(eot|otf).*$/,
        use: [
          {
            loader: 'file-loader',
            options: {
              name: '[name].[ext]',
              outputPath: 'fonts',
            },
          },
        ],
      },
    ],
  },
  resolve: {
    extensions: ['.js', '.jsx'],
    unsafeCache: false,
  },
  devServer: {
    stats: {
      errors: false,
      errorDetails: false,
      warnings: false,
    },
  },
  plugins: [
    new MiniCssExtractPlugin({
      filename: '[name].css',
    }),
  ],
  node: {
    fs: 'empty',
  },
}