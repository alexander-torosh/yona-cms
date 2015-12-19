/**
 * $.hoverDelayed()
 */
(function($) {

    'use strict';

    var oldHover = $.fn.hover,

        newHover = function(handlerIn, handlerOut, delay) {

            return this.each(function() {

                var timeout,
                    handler = function(el, fn, e) {
                        if (timeout) {
                            timeout = window.clearTimeout(timeout);
                        } else {
                            timeout = window.setTimeout(function() {
                                timeout = undefined;
                                fn.call(el, e);
                            }, delay);
                        }
                    };

                $(this).on('mouseenter mouseleave', function(e) {
                    handler(this, e.type === 'mouseenter' ? handlerIn : handlerOut, e);
                });

            });

        };

    $.fn.hoverDelayed = function() {

        var args = Array.prototype.slice.call(arguments);

        if (args.length === 3 && typeof args[2] === 'number') {
            return newHover.apply(this, args);
        } else if (args.length === 2 && typeof args[1] === 'number') {
            return newHover.call(this, args[0], args[0], args[1]);
        }
        return oldHover.apply(this, args);

    };
})(window.jQuery);