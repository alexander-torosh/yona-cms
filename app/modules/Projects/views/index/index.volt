<div class="projects list">

    <div class="limit">
        <section>Отображать по:</section>
        <ul class="numbers">
            <li><a href="/projects?limit=6" class="ajax{% if limit == 6 %} active{% endif %}">6</a></li>
            <li><a href="/projects?limit=12" class="ajax{% if limit == 12 %} active{% endif %}">12</a></li>
            <li><a href="/projects?limit=all" class="infinity ajax{% if limit == "all" %} active{% endif %}">
                    &nbsp;</a></li>
        </ul>
    </div>

    {% for item in paginate.items %}
        {% set image = helper.image([
        'id': item.getFirstImageId(),
        'type': 'project',
        'width': 425,
        'height': 255,
        'strategy': 'a'
        ]) %}
        <a href="/project/{{ item.getId() }}" class="item ajax">
            <img src="{{ image.cachedRelPath() }}" alt="">
            <section class="border">
                <section class="description">
                    <section class="title">{{ item.getTitle() }}</section>
                    <section class="location">{{ item.getLocationCity() }}</section>
                    <section class="zoom"></section>
                </section>
            </section>
        </a>
    {% endfor %}

</div>

{% if paginate.total_pages > 1 %}
    <div class="pagination">
        {{ partial('main/pagination', ['paginate':paginate, 'url':'/projects'] ) }}
    </div>
{% endif %}


