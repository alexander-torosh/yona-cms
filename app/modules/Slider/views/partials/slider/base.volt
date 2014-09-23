{% set images = slider.cachedImages() %}
<div class="yona slider"
     data-rotation="target: .items > .item; pickerItems: .nav > li; nav: .arrows; interval: {{ slider.getDelay() }}; animationSpeed: {{ slider.getAnimationSpeed() }};">
    <div class="arrows">
        <div class="nav-el prev">prev</div>
        <div class="nav-el next">next</div>
    </div>
    <ul class="nav">
        {% for item in images %}
            <li class="{% if 1 == loop.index %} active{% endif %}" data-pos="{{ loop.index }}">{{ loop.index }}</li>
        {% endfor %}
    </ul>
    <section class="items">
        {% for image in images %}
            <section class="item{% if 1 == loop.index %} active{% endif %}">
                <a{% if image.getLink() %} href="{{ image.getLink() }}"{% endif %}{% if image.getCaption() %} title="{{ image.getCaption() }}"{% endif %} rel="nofollow">
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
            </section>
        {% endfor %}
    </section>
</div>