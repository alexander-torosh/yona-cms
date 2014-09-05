<br />-------slider begin-------
{% if slider is not empty %}
    <div id="slider-{{ slider.getId() }}" data-rotation="target: .content > .item; targetRel: .image > .item; pickerItems: .content > .nav > li; interval: {{ slider.getDelay() }};">

        <div class="nav-el prev">prev</div>
        <div class="nav-el next">next</div>
        <section class="image">
            {% for item in slider.getSliderImages() %}
                <section class="item{% if 1 == loop.index %} active{% endif %}">
                    <a href="{{ item.getLink() }}" title="{{ item.getCaption() }}" rel="nofollow">
                        {% set image = helper.image([
                            'type': 'slider',
                            'id': item.getId(),
                            'width': 300,
                            'height': 170,
                            'strategy': 'a',
                            'widthHeight': false
                        ]) %}
                        {{ image.imageHTML() }}
                    </a>
                </section>
            {% endfor %}
        </section>
        <section class="content">
            <ul class="nav">
                {% for item in slider.getSliderImages() %}
                    <li class="{% if 1 == loop.index %} active{% endif %}" data-pos="{{ loop.index }}">{{ loop.index }}</li>
                {% endfor %}
            </ul>
            {% for item in slider.getSliderImages() %}
            <section class="item{% if 1 == loop.index %} active{% endif %}">
                    <a href="{{ item.getLink() }}" rel="nofollow">{{ item.getCaption() }}</a>
            </section>
            {% endfor %}
        </section>
    </div>
{% endif %}
-------/slider end-------<br />