/**
 * Events. Універсальна бібліотека для роботи з подіями
 **/
Event = (function() {

    var guid = 0;

    function fixEvent(event) {
        event = event || window.event;

        if (event.isFixed) {
            return event;
        }
        event.isFixed = true;

        event.preventDefault = event.preventDefault || function() {
            this.returnValue = false;
        };
        event.stopPropagation = event.stopPropagaton || function() {
            this.cancelBubble = true;
        };

        if (!event.target) {
            event.target = event.srcElement;
        }

        if (!event.relatedTarget && event.fromElement) {
            event.relatedTarget = event.fromElement === event.target ? event.toElement : event.fromElement;
        }

        if (event.pageX === null && event.clientX !== null) {
            var html = document.documentElement, body = document.body;
            event.pageX = event.clientX + (html && html.scrollLeft || body && body.scrollLeft || 0) - (html.clientLeft || 0);
            event.pageY = event.clientY + (html && html.scrollTop || body && body.scrollTop || 0) - (html.clientTop || 0);
        }

        if (!event.which && event.button) {
            event.which = (event.button & 1 ? 1 : (event.button & 2 ? 3 : (event.button & 4 ? 2 : 0)));
        }

        return event;
    }

    /* Вызывается в контексте элемента всегда this = element */
    function commonHandle(event) {
        event = fixEvent(event);

        var handlers = this.events[event.type];

        for (var g in handlers) {
            var handler = handlers[g];

            var ret = handler.call(this, event);
            if (ret === false) {
                event.preventDefault();
                event.stopPropagation();
            }
        }
    }

    return {
        add: function(elem, type, handler) {
            if (elem.setInterval && (elem !== window && !elem.frameElement)) {
                elem = window;
            }

            if (!handler.guid) {
                handler.guid = ++guid;
            }

            if (!elem.events) {
                elem.events = {};
                elem.handle = function(event) {
                    if (typeof Event !== "undefined") {
                        return commonHandle.call(elem, event);
                    }
                };
            }

            if (!elem.events[type]) {
                elem.events[type] = {}

                if (elem.addEventListener)
                    elem.addEventListener(type, elem.handle, false);
                else if (elem.attachEvent)
                    elem.attachEvent("on" + type, elem.handle);
            }

            elem.events[type][handler.guid] = handler;
        },
        remove: function(elem, type, handler) {
            var handlers = elem.events && elem.events[type]

            if (!handlers)
                return;

            delete handlers[handler.guid];

            for (var any in handlers)
                return;
            if (elem.removeEventListener)
                elem.removeEventListener(type, elem.handle, false);
            else if (elem.detachEvent)
                elem.detachEvent("on" + type, elem.handle);

            delete elem.events[type];


            for (var any in elem.events)
                return;
            try {
                delete elem.handle;
                delete elem.events;
            } catch (e) { // IE
                elem.removeAttribute("handle");
                elem.removeAttribute("events");
            }
        }
    };
}());

/*<!--DOM Node ready-->*/
function bindReady(handler) {

    var called = false

    function ready() {
        if (called)
            return
        called = true
        handler()
    }

    if (document.addEventListener) { // native event
        document.addEventListener("DOMContentLoaded", ready, false)
    } else if (document.attachEvent) {  // IE

        try {
            var isFrame = window.frameElement != null
        } catch (e) {
        }

        // IE, the document is not inside a frame
        if (document.documentElement.doScroll && !isFrame) {
            function tryScroll() {
                if (called)
                    return
                try {
                    document.documentElement.doScroll("left")
                    ready()
                } catch (e) {
                    setTimeout(tryScroll, 10)
                }
            }
            tryScroll()
        }

        // IE, the document is inside a frame
        document.attachEvent("onreadystatechange", function() {
            if (document.readyState === "complete") {
                ready()
            }
        })
    }

    // Old browsers
    if (window.addEventListener)
        window.addEventListener('load', ready, false)
    else if (window.attachEvent)
        window.attachEvent('onload', ready)
    else {
        var fn = window.onload // very old browser, copy old onload
        window.onload = function() { // replace by new onload and call the old one
            fn && fn()
            ready()
        }
    }
}

var readyList = [];

function onReady(handler) {

    function executeHandlers() {
        for (var i = 0; i < readyList.length; i++) {
            readyList[i]();
        }
    }

    if (!readyList.length) { // set handler on first run
        bindReady(executeHandlers);
    }

    readyList.push(handler);
}
/*<!--/DOM Node ready-->*/

if (!String.prototype.trim) {
    String.prototype.trim = function() {
        return this.replace(/^\s+|\s+$/g, '');
    };
}

$.fn.serializeObject = function()
{
    var o = {};
    var a = this.serializeArray();
    $.each(a, function() {
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};

function isLocalStorageAvailable() {
    try {
        return 'localStorage' in window && window['localStorage'] !== null;
    } catch (e) {
        return false;
    }
}