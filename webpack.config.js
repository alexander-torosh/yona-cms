'use strict';

const ExtractTextPlugin = require('extract-text-webpack-plugin');

const path = require('path');
const fs = require('fs');

const APP_PATH = path.resolve(__dirname, 'app', 'assets');
const DIST_PATH = path.resolve(__dirname, 'public', 'dist');

const JavaScriptConfig = {
  entry: {
    main: path.join(APP_PATH, 'scripts', 'main.index.js'),
    admin: path.join(APP_PATH, 'scripts', 'admin.index.js')
  },
  output: {
    filename: '[name].js',
    path: DIST_PATH
  },
  plugins: [
    function () {
      this.plugin("done", function (stats) {
        require("fs").writeFileSync(
          path.join(path.resolve(__dirname, 'app', 'data', 'assets'), "scripts.json"),
          JSON.stringify(stats.toJson())
        );
      });
    }
  ]
};

if (process.env.NODE_ENV === 'development') {
  JavaScriptConfig['devtool'] = "cheap-eval-source-map";
}

const extractSass = new ExtractTextPlugin({
  filename: "[name].css"
});

const StylesheetConfig = {
  entry: {
    main: path.join(APP_PATH, 'styles', 'main.scss'),
    admin: path.join(APP_PATH, 'styles', 'admin.scss')
  },
  output: {
    filename: '[name].css',
    path: DIST_PATH
  },
  module: {
    rules: [{
      test: /\.scss$/,
      loader: extractSass.extract({
        loader: [{
          loader: "css-loader",
          options: {
            minimize: true,
            sourceMap: true
          }
        }, {
          loader: "sass-loader"
        }],
        // use style-loader in development
        fallbackLoader: "style-loader"
      })
    }]
  },
  plugins: [
    extractSass,
    function () {
      this.plugin("done", function (stats) {
        require("fs").writeFileSync(
          path.join(path.resolve(__dirname, 'app', 'data', 'assets'), "styles.json"),
          JSON.stringify(stats.toJson())
        );
      });
    }
  ]
};

module.exports = [
  JavaScriptConfig,
  StylesheetConfig
];