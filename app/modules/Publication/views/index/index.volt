<h1>{{ title }}</h1>

<div class="publications {{ format }}">

    {% if paginate.total_items > 0 %}
        {% for item in paginate.items %}
            {{ helper.modulePartial('index/format/' ~ format, ['item':item]) }}
        {% endfor %}
    {% else %}
        <p>{{ helper.translate('Entries not found') }}</p>
    {% endif %}

</div>

{% if paginate.total_pages > 1 %}
    <div class="pagination">
        {{ partial('main/pagination', ['paginate':paginate] ) }}
    </div>
{% endif %}