function AjaxViewModel() {
    var self = this;

    self.History = window.History;

    self.manualStateChange = true;

    History.Adapter.bind(window, 'statechange', function () {
        if (self.manualStateChange == true) {
            window.location.reload();
        }
        self.manualStateChange = true;
    });

    self.bind = function () {
        var ajaxButtons = document.querySelectorAll('a');
        for (var i = 0; i < ajaxButtons.length; i++) {
            var ajaxButton = ajaxButtons[i];
            if (ajaxButton.href && ajaxButton.href != '#' && !ajaxButton.classList.contains('noajax')) {
                var url = new URL(ajaxButton.href).hostname;
                if (url == window.location.hostname) {
                    ajaxButton.addEventListener('click', self.click, false);
                }
            }
        }
    }

    self.click = function (e) {
        if (!self.History.enabled) {
            console.log('history disabled');
            return true;
        }

        var url = this.href;

        self.preUpdate();
        self.getData(url);
        self.manualStateChange = false;

        e = e || window.event
        e.preventDefault ? e.preventDefault() : (e.returnValue = false)
    }

    self.getData = function (url) {
        self.preUpdate();

        $.ajax(url,{
            data: {_ajax: true},
            dataType: "json"
        }).done(function(response) {
            if (response.success) {
                self.update(response, url);
                self.postUpdate(response);
            }
        }).fail(function() {
            alert('Ошибка загрузки страницы');
        });
    }

    self.preUpdate = function () {
        //document.body.style.opacity = 0.2;
    }

    self.update = function (response, href) {
        self.History.pushState({href: href}, response.title, href);

        var main = document.getElementById('wrapper');
        main.innerHTML = response.html;
        self.forceRedraw(main);
    }

    self.postUpdate = function (response) {
        var rotation = new Rotation();
        rotation.init();

        self.bind();

        $('html,body').animate({
            scrollTop: 0
        }, 300);

        //document.body.style.opacity = 1;

        if (response) {
            document.body.setAttribute('class','');
            if (response.bodyClass) {
                document.body.classList.add(response.bodyClass);
            }
        }
    }

    self.forceRedraw = function (element) {
        if (!element) {
            return;
        }

        var n = document.createTextNode(' ');
        var disp = element.style.display;

        element.appendChild(n);
        element.style.display = 'none';

        setTimeout(function () {
            element.style.display = disp;
            n.parentNode.removeChild(n);
        }, 50);
    }

}

var Ajax = new AjaxViewModel();

onReady(function () {
    Ajax.bind();
});