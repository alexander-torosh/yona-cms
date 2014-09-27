{% set images = slider.cachedImages() %}
<div class="yona slider"
     data-rotation="target: .slides > .items > .item; pickerItems: .nav > ul > li; nav: .slides; interval: {{ slider.getDelay() }}; animationSpeed: {{ slider.getAnimationSpeed() }};">
    <div class="slides">
        <div class="prev"><i class="fa fa-arrow-left"></i></div>
        <div class="next"><i class="fa fa-arrow-right"></i></div>
        <div class="items">
            {% for image in images %}
                <div class="item{% if 1 == loop.index %} active{% endif %}">
                    <a{% if image.getLink() %} href="{{ image.getLink() }}"{% endif %}{% if image.getCaption() %} title="{{ image.getCaption() }}"{% endif %}
                            rel="nofollow">
                        {% set img = helper.image([
                        'type': 'slider',
                        'id': image.getId(),
                        'width': 1000,
                        'height': 300,
                        'strategy': 'a',
                        'widthHeight': false
                        ]) %}
                        {{ img.imageHTML() }}
                    </a>
                    {% if image.getCaption() %}
                        <section class="caption">{{ image.getCaption() }}</section>
                    {% endif %}
                </div>
            {% endfor %}
        </div>
    </div>
    <div class="nav">
        <ul>
            {% for item in images %}
                <li class="fa fa-circle{% if 1 == loop.index %} active{% endif %}"
                    data-pos="{{ loop.index }}"></li>
            {% endfor %}
        </ul>
    </div>
</div>