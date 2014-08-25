<h1>{{ title }}</h1>

<div class="publications">

    {% if paginate.total_items > 0 %}
        {% for item in paginate.items %}
            {% set image = helper.image([
            'id': item.getId(),
            'type': 'publication',
            'width': 240,
            'strategy': 'w'
            ]) %}
            {% set link = url(['for':'publication', 'type':item.getType(), 'slug':item.getSlug()]) %}
            {% if image.isExists() %}{% set imageExists = true %}{% else %}{% set imageExists = false %}{% endif %}
            <div class="item{% if imageExists %} with-image{% endif %}">
                {% if imageExists %}
                    <a class="image" href="{{ link }}">{{ image.imageHTML() }}</a>
                {% endif %}
                <div class="text">
                    <a href="{{ link }}" class="title">{{ item.getTitle() }}</a>
                    <section class="announce">{{ helper.announce(item.getText(), 300) }}</section>
                    <a href="{{ link }}" class="details">Подробнее &rarr;</a>
                </div>
            </div>
        {% endfor %}
    {% else %}
        <p>Публикации отсутствуют</p>
    {% endif %}

</div>

{% if paginate.total_pages > 1 %}
    <div class="pagination">
        {{ partial('main/pagination', ['paginate':paginate, 'url':publicationsLink] ) }}
    </div>
{% endif %}