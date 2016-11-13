{% if entries %}
    <div class="widget last publications">
        <h3>{{ helper.translate('Latest news') }}</h3>

        <div class="items">
            {% for item in entries %}
                {% set url = helper.langUrl([
                'for':'publication',
                'type':item.t_slug,
                'slug':item.p.getSlug()
                ]) %}
                {% set image = helper.image([
                'id':item.p.getId(),
                'type':'publication',
                'width': 100,
                'strategy': 'w'
                ],[
                'alt':item.title|escape_attr
                ]) %}
                <div class="item">
                    {% if image.isExists() %}
                        <a href="{{ url }}" title="{{ item.title|escape_attr }}" class="image" rel="nofollow">
                            {{ image.imageHTML() }}
                        </a>
                    {% endif %}
                    <div class="content">
                        <a href="{{ url }}" title="{{ item.title|escape_attr }}" class="title">
                            {{ item.title }}
                        </a>

                        <div class="date">{{ item.p.getDate('d.m.Y') }}</div>

                        <div class="announce">
                            {{ helper.announce(item.text, 130) }}
                        </div>
                    </div>
                </div>
            {% endfor %}
        </div>
    </div>
{% endif %}