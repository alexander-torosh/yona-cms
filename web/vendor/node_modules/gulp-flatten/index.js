var path = require('path');
var through2 = require('through2');
var PluginError = require('gulp-util').PluginError;

module.exports = function(opts) {
  opts = opts || {};
  opts.newPath = opts.newPath || '';

  return through2.obj(function(file, enc, next) {
    if (!file.isDirectory()) {
      try {
        file.path = path.join(file.base, opts.newPath, flattenPath(file));
        this.push(file);
      } catch (e) {
        this.emit('error', new PluginError('gulp-flatten', e));
      }
    }
    next();
  });

  /**
   * Flatten the path to the desired depth
   * 
   * @param {File} file - vinyl file
   * @return {String}
   */
  function flattenPath(file) {
    if (!opts.includeParents) {
      return path.basename(file.path);
    }
    
    var dirname = path.dirname(file.relative);
    var dirs = dirname.split(path.sep);
    if (opts.includeParents > dirs.length) {
      return file.relative;
    }
    
    var newPath = [];
    while (newPath.length < opts.includeParents) {
      newPath.push(dirs.shift());
    }
    newPath.push(path.basename(file.path));
    
    return path.join.apply(path, newPath);
  }
};
