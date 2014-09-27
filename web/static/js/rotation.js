/**
 * Rotation
 * Documentation
 *
 * Библиотека для универсального управления элементами со сменным содержимим по принципу "листалки" или "rotation"
 *
 * JS:
 * // Подключаем 1 раз
 * <script src="your-path/rotation.js"></script>
 *
 * // Инициализируем
 * <script>
 *     var rotation = new Rotation();
 *     rotation.init();
 * </script>
 *
 *
 * HTML:
 * <div data-rotation="target: .items > .item; interval: 2; animationSpeed: 300">
 *     <div class="items">
 *         <div class="item"><img src="http://wezoom.net/images/layout/home-banner/1.png"></div>
 *         <div class="item"><img src="http://wezoom.net/images/layout/home-banner/2.png"></div>
 *         <div class="item"><img src="http://wezoom.net/images/layout/home-banner/3.png"></div>
 *     </div>
 * </div>
 *
 *
 * Для инициализации элемента, необходимо добавить атрибут data-rotation="" для корневого HTML-элемента.
 * Атрибут должен содержать конфигурацию
 * Пример конфигурации: data-rotation="target: .items > .item; interval: 2; animationSpeed: 300; nav: .nav-sm; current: .nav-counter > .current"
 *
 * Перечень свойств конфигурации:
 * target - (селектор) элементы, которые будут перелистываться
 *
 * interval - (секунды) интервал смены текущей позиции ротатора
 *
 * animationSpeed - (миллисекунды) время анимации смены активного элемента
 *
 * nav - (селектор) элемент навигации. HTML-элемент, который соотв. селектору, должен содержать внутри два HTML-элемента с классами: "prev" и "next"
 *
 * current - (селектор) - элемент, который находится внутри селектора "nav". Отвечает за визуальный вывод текущей позиции ротатора.
 *
 * pickerItems - (селектор) элементы, которые используются для точечного выбора текущей позиции.
 * Элементы должны содержать атрибут data-pos="1" со значением позиции на которую произойдет перелистывание при клике на этот элемент.
 *
 * targetRel - (селектор) сопряженные элементы, которые перелистываются при изменении текущей позиции при перелистывании.
 *
 * Если любое из свойств не задано - оно не используется. Значений по умолчанию нет.
 **/
function Rotation() {

    var self = this;
    self.elements = [];
    self.timerPosition = 1;

    self.init = function () {
        var elements = document.querySelectorAll('[data-rotation]');
        for (var i = 0; i < elements.length; i++) {
            var elem = elements[i];
            elem.classList.add('rotation');
            var config = self.parseConfig(elem.getAttribute('data-rotation'));
            if (config.target) {
                var targetElements = elem.querySelectorAll(config.target);
                self.initTargetElements(targetElements);
                elem.setAttribute('data-count', targetElements.length);
                elem.setAttribute('data-current', '1');
                if (config.interval) {
                    elem.setAttribute('data-trigger', '0');
                }
            }
            var navElement;
            if (config.nav) {
                navElement = elem.querySelector(config.nav);
            } else {
                navElement = elem;
            }
            var prevElement = navElement.querySelector('.prev');
            if (prevElement) {
                prevElement.addEventListener("click", self.clickPrev, false);
            }
            var nextElement = navElement.querySelector('.next');
            if (nextElement) {
                nextElement.addEventListener("click", self.clickNext, false);
            }
            if (config.pickerItems) {
                var pickerItems = elem.querySelectorAll(config.pickerItems);
                for (p = 0; p < pickerItems.length; p++) {
                    var pickerItemNode = pickerItems[p];
                    if (pickerItemNode) {
                        pickerItemNode.addEventListener("click", self.pick, false);
                    }
                }
            }
            if (config.interval) {
                elem.setAttribute('data-interval', config.interval);
            }
            self.elements.push(elem);
            self.setVisibleByActive(elem);
        }
        self.initIntervals();
    };

    self.initTargetElements = function (targetElements) {
        for (var i = 0; i < targetElements.length; i++) {
            var elem = targetElements[i];
            if (i != 0) {
                elem.style.display = 'none';
                elem.style.opacity = 0;
            }
        }
    }

    self.initIntervals = function () {
        window.setInterval(function () {
            for (var i = 0; i < self.elements.length; i++) {
                var elem = self.elements[i];
                var trigger = elem.getAttribute('data-trigger') || 0;
                var interval = elem.getAttribute('data-interval') || 0;
                if (trigger == 0) {
                    if (interval > 0) {
                        if (self.timerPosition >= interval && self.timerPosition % interval == 0) {
                            self.next(elem);
                        }
                    }
                }
            }
            self.timerPosition++;
        }, 1000);
    };

    self.getRoot = function (element) {
        var parent = element.parentNode;
        if (parent) {
            if (nodeHasClass(parent, 'rotation')) {
                return parent;
            } else {
                return self.getRoot(parent);
            }
        }
        return false;
    };

    self.parseConfig = function (configString) {
        var config = {};
        $.each(configString.split(';'), function (index, value) {
            var keyVal = value.split(':');
            if (keyVal[0] && keyVal[1]) {
                config[keyVal[0].trim()] = keyVal[1].trim();
            }
        });
        return config;
    };

    self.clickPrev = function (e) {
        var rootNode = self.getRoot(e.target);
        self.triggerUp(rootNode);
        self.prev(rootNode);
    };

    self.clickNext = function (e) {
        var rootNode = self.getRoot(e.target);
        self.triggerUp(rootNode);
        self.next(rootNode);
    };

    self.pick = function (e) {
        var rootNode = self.getRoot(e.target);
        self.triggerUp(rootNode);
        var newPosition = e.target.getAttribute('data-pos');
        self.setPosition(newPosition, rootNode);
    };

    self.prev = function (rootNode) {
        var current = self.getCurrent(rootNode);
        var newPosition = (current === 1) ? self.getCount(rootNode) : current - 1;
        self.setPosition(newPosition, rootNode);
    };

    self.next = function (rootNode) {
        var current = self.getCurrent(rootNode);
        var newPosition = (current === self.getCount(rootNode)) ? 1 : current + 1;
        self.setPosition(newPosition, rootNode);
    };

    self.triggerUp = function (rootNode) {
        if (rootNode) {
            rootNode.setAttribute('data-trigger', '1');
        }
    };

    self.getCurrent = function (rootNode) {
        return parseInt(rootNode.getAttribute('data-current'));
    };

    self.getCurrentElement = function (rootNode) {
        var config = self.getConfig(rootNode);
        var currentElement = rootNode.querySelector(config.nav + ' ' + config.current);
        return currentElement;
    };

    self.getTargetElements = function (rootNode) {
        var config = self.getConfig(rootNode);
        var targetElements = rootNode.querySelectorAll(config.target);
        return targetElements;
    };

    self.getTargetRelElements = function (rootNode) {
        var config = self.getConfig(rootNode);
        var targetRelElements = rootNode.querySelectorAll(config.targetRel);
        return targetRelElements;
    };

    self.getPickerElements = function (rootNode) {
        var config = self.getConfig(rootNode);
        var pickerElements = rootNode.querySelectorAll(config.pickerItems);
        return pickerElements;
    };

    self.setTargetActive = function (newPosition, rootNode, targetElements, slide) {
        var i, animationSpeed = null;
        var config = self.getConfig(rootNode);
        if (config.animationSpeed) {
            animationSpeed = config.animationSpeed;
        }
        for (i = 0; i <= targetElements.length; i++) {
            var itemNode = targetElements[i];
            if ((newPosition - 1) === i) {
                if (itemNode) {
                    itemNode.classList.add('active');
                    if (slide === true) {
                        if (animationSpeed != null) {
                            $(itemNode).css('display', 'block').animate({opacity: 1}, animationSpeed);
                        } else {
                            itemNode.style.display = 'block';
                            itemNode.style.opacity = 1;
                        }
                    }
                }
            } else {
                if (itemNode) {
                    itemNode.classList.remove('active');
                    if (slide === true) {
                        if (animationSpeed != null) {
                            $(itemNode).css('display', 'none').animate({opacity: 0}, animationSpeed);
                        } else {
                            itemNode.style.display = 'none';
                            itemNode.style.opacity = 0;
                        }
                    }
                }
            }
        }
    };

    self.getCount = function (rootNode) {
        return parseInt(rootNode.getAttribute('data-count'));
    };

    self.getConfig = function (rootNode) {
        return self.parseConfig(rootNode.getAttribute('data-rotation'));
    };

    self.setPosition = function (newPosition, rootNode) {
        rootNode.setAttribute('data-current', newPosition);
        var currentElement = self.getCurrentElement(rootNode);
        if (currentElement) {
            currentElement.innerHTML = newPosition;
        }
        self.setTargetActive(newPosition, rootNode, self.getTargetElements(rootNode), true);
        self.setTargetActive(newPosition, rootNode, self.getTargetRelElements(rootNode), false);
        self.setTargetActive(newPosition, rootNode, self.getPickerElements(rootNode), false);
    };

    self.setVisibleByActive = function (element) {
        var activeItem = element.querySelector('.wrapper > .cluster > .item.active');
        if (activeItem) {
            var activeParent = activeItem.parentNode;
            var index = 0;
            while (activeParent = activeParent.previousSibling) {
                if (activeParent.nodeType === 1) {
                    ++index
                }
            }
            self.setPosition(index, element);
        }
    }

    return {
        init: self.init
    };

}

function nodeHasClass(el, clss) {
    return el.className && new RegExp("(^|\\s)" + clss + "(\\s|$)").test(el.className);
}