<h1>{{ title }}</h1>

<div class="publications">

    <div class="limit">
        <section>Отображать по:</section>
        <ul class="numbers">
            {% set publicationsLink = url(['for':'publications','type':type]) %}
            <li><a href="{{ publicationsLink }}?limit=5" class="ajax{% if limit == 5 %} active{% endif %}">5</a>
            </li>
            <li><a href="{{ publicationsLink }}?limit=10" class="ajax{% if limit == 10 %} active{% endif %}">10</a>
            </li>
            <li><a href="{{ publicationsLink }}?limit=all"
                   class="infinity ajax{% if limit == "all" %} active{% endif %}">
                    &nbsp;</a></li>
        </ul>
    </div>

    {% for item in paginate.items %}
        <div class="item">
            <div class="row">
                {% set image = helper.image([
                'id': item.getId(),
                'type': 'publication',
                'width': 205,
                'strategy': 'w'
                ]) %}
                {% set link = url(['for':'publication', 'type':item.getType(), 'slug':item.getSlug()]) %}
                <a class="image" href="{{ link }}">{{ image.imageHTML() }}</a>

                <div class="text">
                    <a href="{{ link }}" class="title">{{ item.getTitle() }}</a>
                    <section class="announce">{{ helper.announce(item.getText(), 300) }}</section>
                </div>
            </div>
        </div>
    {% endfor %}

</div>

{% if paginate.total_pages > 1 %}
    <div class="pagination">
        {{ partial('main/pagination', ['paginate':paginate, 'url':publicationsLink] ) }}
    </div>
{% endif %}