# [gulp](http://gulpjs.com)-chmod [![Build Status](https://travis-ci.org/sindresorhus/gulp-chmod.svg?branch=master)](https://travis-ci.org/sindresorhus/gulp-chmod)

> Change permissions of [Vinyl](https://github.com/wearefractal/vinyl) files


## Install

```sh
$ npm install --save-dev gulp-chmod
```


## Usage

```js
var gulp = require('gulp');
var chmod = require('gulp-chmod');

gulp.task('default', function () {
	return gulp.src('src/app.js')
		.pipe(chmod(755))
		.pipe(gulp.dest('dist'));
});
```

or

```js
var gulp = require('gulp');
var chmod = require('gulp-chmod');

gulp.task('default', function () {
	return gulp.src('src/app.js')
		.pipe(chmod({
			owner: {
				read: true,
				write: true,
				execute: true
			},
			group: {
				execute: true
			},
			others: {
				execute: true
			}
		}))
		.pipe(gulp.dest('dist'));
});
```


## API

### chmod(mode)

#### mode

Type: `number`, `object`

Can either be a [chmod](http://ss64.com/bash/chmod.html) mode number or an object with the individual permissions specified.


Values depends on the current file, but these are the possible keys:

```js
{
	owner: {
		read: true,
		write: true,
		execute: true
	},
	group: {
		read: true,
		write: true,
		execute: true
	},
	others: {
		read: true,
		write: true,
		execute: true
	}
}
```

When `read`, `write` and `execute` are same, you can simplify the object:

```
{
	read: true
}
```


## Tip

Combine it with [gulp-filter](https://github.com/sindresorhus/gulp-filter) to only change permissions on a subset of the files.

```js
var gulp = require('gulp');
var gFilter = require('gulp-filter');
var chmod = require('gulp-chmod');

var filter = gFilter('src/cli.js');

gulp.task('default', function () {
	return gulp.src('src/*.js')
		// filter a subset of the files
		.pipe(filter)
		// make them executable
		.pipe(chmod(755))
		// bring back the previously filtered out files
		.pipe(filter.restore())
		.pipe(gulp.dest('dist'));
});
```


## License

MIT © [Sindre Sorhus](http://sindresorhus.com)
