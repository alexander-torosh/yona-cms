var ExtractTextPlugin = require('extract-text-webpack-plugin');

var path = require('path');
var fs = require('fs');
var _ = require('lodash');

var APP_PATH = path.resolve(__dirname, 'app', 'assets');
var DIST_PATH = path.resolve(__dirname, 'public', 'dist');

var JavaScriptConfig = {
  entry: {
    main: path.join(APP_PATH, 'scripts', 'main.index.js'),
    admin: path.join(APP_PATH, 'scripts', 'admin.index.js')
  },
  output: {
    filename: '[chunkhash].[name].js',
    path: DIST_PATH
  },
  plugins: [
    function () {
      this.plugin("done", function (stats) {
        require("fs").writeFileSync(
          path.join(path.resolve(__dirname, 'data', 'assets'), "scripts.json"),
          JSON.stringify(stats.toJson())
        );
        // Remove old dist files
        /*try {
         var files = fs.readdirSync(DIST_PATH);
         } catch (e) {
         console.log(e);
         return;
         }
         if (files.length > 0) {
         for (var i = 0; i < files.length; i++) {
         var filePath = DIST_PATH + '/' + files[i];
         if (fs.statSync(filePath).isFile()) {
         if (path.extname(filePath) == '.js') {
         //fs.unlinkSync(filePath);
         //console.log('removed ' + filePath);
         }
         }
         }
         }*/
      });
    }
  ]
};

if (process.env.NODE_ENV == 'development') {
  JavaScriptConfig['devtool'] = "cheap-eval-source-map";
}

const extractSass = new ExtractTextPlugin({
  filename: "[chunkhash].[name].css"
});

var StylesheetConfig = {
  entry: {
    main: path.join(APP_PATH, 'styles', 'main.scss'),
    admin: path.join(APP_PATH, 'styles', 'admin.scss')
  },
  output: {
    filename: '[chunkhash].[name].css',
    path: DIST_PATH
  },
  module: {
    rules: [{
      test: /\.scss$/,
      loader: extractSass.extract({
        loader: [{
          loader: "css-loader"
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
          path.join(path.resolve(__dirname, 'data', 'assets'), "styles.json"),
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