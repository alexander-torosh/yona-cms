/*jshint bitwise:true, curly:true, eqeqeq:true, forin:true, noarg:true, noempty:true, nonew:true, undef:true, strict:true, node:true */
"use strict";

// dependencies
var gulp = require('gulp'),
    prompt = require('./index.js');

gulp.task('default', function() {
    return gulp.src(['./package.json', './index.js']) // get all the files to bump version in
        .pipe(prompt.confirmEach('Have you commited all the changes to be included by this version of <%= file.path %>?'));
});
