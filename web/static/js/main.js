window.onresize = function () {
    // do something on resize window
};

onReady(function () {
    var rotation = new Rotation();
    rotation.init();
});

function callbackClick() {
    callbackOpen();
}

function callbackOpen(message)
{
    var callback = document.getElementById('callback');
    var callbackText = document.getElementById('callback-text');
    callbackText.value = '';
    if (callback.classList.contains('show')) {
        callback.classList.remove('show');
    } else {
        callback.classList.add('show');
    }
    if (message) {
        callbackText.value = message + '\n';
    }

    e = window.event;
    if (e) {
        e.preventDefault ? e.preventDefault() : (e.returnValue = false)
    } else {
        return false;
    }
}

function callbackClose() {
    this.parentNode.parentNode.classList.remove('show');
}

$.noty.defaults = {
    layout: 'center',
    theme: 'defaultTheme',
    type: 'success',
    text: '', // can be html or string
    dismissQueue: true, // If you want to use queue feature set this true
    template: '<div class="noty_message"><span class="noty_text"></span><div class="noty_close"></div></div>',
    animation: {
        open: {height: 'toggle'},
        close: {height: 'toggle'},
        easing: 'swing',
        speed: 500 // opening & closing animation speed
    },
    timeout: 10000, // delay for closing event. Set false for sticky notifications
    force: false, // adds notification to the beginning of queue when set to true
    modal: false,
    maxVisible: 5, // you can set max visible notification for dismissQueue true option,
    killer: false, // for close all notifications before show
    closeWith: ['click'], // ['click', 'button', 'hover']
    callback: {
        onShow: function() {},
        afterShow: function() {},
        onClose: function() {},
        afterClose: function() {}
    },
    buttons: false // an array of buttons
};

function callbackSubmit(form)
{
    var data = $(form).serializeArray();
    $.post("/index/index/callback", data, function(response){
        if (response.success) {
            noty({text: response.successMsg, type: 'success'});
            $(form).children('input').val('');
            $(form).children('textarea').val('');
            $("#callback").removeClass('show');
        }
        if (response.error) {
            noty({text: response.error, type: 'error'});
        }
    },'json');
    return false;
}

function calcSubmit(form)
{
    var data = $(form).serializeArray();
    $.post("/index/index/calc", data, function(response){
        if (response.success) {
            noty({text: response.successMsg, type: 'success'});
            $(form).children('input').val('');
            $(form).children('textarea').val('');
        }
        if (response.error) {
            noty({text: response.error, type: 'error'});
        }
    },'json');
    return false;
}

function menuClick() {
    var menu = document.getElementById('menu');
    //var footer = document.querySelector('footer');

    if (menu.classList.contains('show')) {
        menu.classList.remove('show');
        //footer.classList.remove('menu-bot');
        //document.body.style.height = null;
        //footer.setAttribute('style', null);
        //windowAdaptizeHeight();
    } else {
        menu.classList.add('show');
        //footer.classList.add('menu-bot');
        //document.body.style.height = parseInt(1060 + 85 + 85) + 'px';
        //var footerPos = $('footer').offset().top;
        //var footerNewPos = footerPos + 85 - $('body').height();
        //footer.setAttribute('style', 'bottom: ' + footerNewPos + 'px')
    }


}

function initializeMap() {
    var map_canvas = document.getElementById("map_canvas");
    if (!map_canvas) {
        return;
    }

    var myLatlng = new google.maps.LatLng(50.432358, 30.513167);
    var centerLatlng = new google.maps.LatLng(50.43211, 30.5085);
    var myOptions = {
        zoom: 16,
        center: centerLatlng,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        scrollwheel: false
    }

    var map = new google.maps.Map(map_canvas, myOptions);

    var markerImage = new google.maps.MarkerImage(
        '/static/images/reynaers_map_ico.png',
        new google.maps.Size(58,69),
        new google.maps.Point(0,0),
        new google.maps.Point(31,63)
    );

    var marker = new google.maps.Marker({
        icon: markerImage,
        position: myLatlng,
        map: map,
        title:"ул. Горького 50"
    });

}

function initFancybox()
{
    $(".fancybox").fancybox({
        openEffect: 'elastic',
        closeEffect: 'elastic',
        prevEffect: 'none',
        nextEffect: 'none',
        helpers: {
            title: {
                type: 'over'
            }
        }
    });
}