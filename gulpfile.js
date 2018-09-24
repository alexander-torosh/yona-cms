const gulp = require('gulp');
const concat = require('gulp-concat');
const fs = require('fs')
const path = require('path')

const webpack = require('webpack');
const webpackStream = require('webpack-stream');
const named = require('vinyl-named');

const webpackConfig = require('./webpack.config.js');

gulp.task('modules', () => {
  const modulesFile = path.resolve(__dirname, 'src', 'app', 'config', 'modules.json')
  const modulesDir = path.resolve(__dirname, 'src', 'modules')

  const modulesContents = fs.readFileSync(modulesFile)
  if (modulesContents.length > 0) {

    const modules = JSON.parse(modulesContents)
    if (modules.length > 0) {

      const gulpSources = []
      Object.values(modules).forEach((module) => {

        const moduleIndexFile = path.resolve(modulesDir, module, 'assets', 'js', 'index.js')
        if (fs.existsSync(moduleIndexFile)) {

          gulpSources.push(moduleIndexFile)
        }
      })

      console.log(gulpSources)

      /* gulp.src(gulpSources)
        .pipe(concat('modules.js'))
        .pipe(gulp.dest('./public/dist/')); */

      // gulp.src(gulpSources)
        // .pipe(named())
        // .pipe(webpackStream(webpackConfig), webpack)
        // .pipe(gulp.dest('./public/dist/modules/'));
    }
  }
})