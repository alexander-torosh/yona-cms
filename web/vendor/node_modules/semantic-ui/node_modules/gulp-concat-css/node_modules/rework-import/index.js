'use strict';

var css = require('css');
var fs = require('fs');
var globby = require('globby');
var parse = require('parse-import');
var path = require('path');
var urlRegex = require('url-regex');

/**
 * Get options
 *
 * @param {Array} rules
 * @param {Object} opts
 * @api private
 */

function getOptions(rules, opts) {
    var dir;
    var obj = {
        source: opts.source,
        transform: opts.transform || function (val) {
            return val;
        }
    };

    obj.path = opts.path || [];
    obj.path = Array.isArray(obj.path) ? obj.path : [obj.path];

    if (rules.length && rules[0].position && rules[0].position.source) {
        dir = path.dirname(rules[0].position.source);
    }

    if (dir && obj.path.indexOf(dir) === -1) {
        obj.path.unshift(dir);
    }

    if (!obj.path.length) {
        obj.path.push(process.cwd());
    }

    return obj;
}

/**
 * Create error
 *
 * @param {String} file
 * @param {String} src
 * @param {Array} paths
 * @api private
 */

function createError(file, src, paths) {
    var err = ['Failed to find ' + file];

    if (src) {
        err.push('from ' + src);
    }

    err.push([
        'in [',
        '    ' + paths.join(',\n    '),
        ']'
    ].join('\n'));

    return err.join(' ');
}

/**
 * Check if a file exists
 *
 * @param {String} file
 * @param {String} src
 * @param {Object} opts
 * @api private
 */

function exists(file, src, opts) {
    var files = opts.path.map(function (dir) {
        return path.join(dir, file);
    });

    files = globby.sync(files);

    if (!files.length) {
        throw new Error(createError(file, src, opts.path));
    }

    return files[0];
}

/**
 * Read the contents of a file
 *
 * @param {String} file
 * @param {Object} opts
 * @api private
 */

function read(file, opts) {
    var encoding = opts.encoding || 'utf8';
    var data = opts.transform(fs.readFileSync(file, encoding));
    return css.parse(data, {source: file}).stylesheet;
}

/**
 * Run
 *
 * @param {Object} style
 * @param {Object} opts
 * @api private
 */

function run(style, opts) {
    opts = getOptions(style.rules, opts || {});

    var rules = style.rules || [];
    var ret = [];

    rules.forEach(function (rule) {
        if (rule.type !== 'import') {
            ret.push(rule);
            return;
        }

        var importRule = '@import ' + rule.import + ';';
        var data = parse(importRule)[0];
        var pos = rule.position ? rule.position.source : null;

        if (urlRegex({ exact: true }).test(data.path)) {
            ret.push(rule);
            return;
        }

        opts.source = exists(data.path, pos, opts);

        if (opts.path.indexOf(path.dirname(opts.source)) === -1) {
            opts.path.unshift(path.dirname(opts.source));
        }

        var content = read(opts.source, opts);
        run(content, opts);

        if (!data.condition || !data.condition.length) {
            ret = ret.concat(content.rules);
            return;
        }

        ret.push({
            media: data.condition,
            rules: content.rules,
            type: 'media'
        });
    });

    style.rules = ret;
}

/**
 * Module exports
 */

module.exports = function (opts) {
    return function (style) {
        run(style, opts);
    };
};
