<!--controls-->
<div class="ui segment">

    <a href="/publication/admin/{{ type }}/add" class="ui button positive">
        <i class="icon plus"></i> Добавить
    </a>

    <a href="/publication/type" class="ui button">
        <i class="icon list"></i> Типы публикаций
    </a>

</div>
<!--/end controls-->

<div class="ui tabular menu">
    {% for type_el in types %}
    <a href="{{ url(['for':'publications_admin','type':type_el.getSlug()]) }}?lang={{ constant('LANG') }}" class="item{% if type_el.getId() == type_id%} active{% endif %}">
        {{ type_el.getTitle() }}
    </a>
    {% endfor %}
</div>

{% if paginate.total_items > 0 %}

        <table class="ui compact table small segment">
            <tr>
                <th style="width: 100px"></th>
                <th>Название</th>
                <th>Тип публикации</th>
                <th>Дата публикации</th>
                <th>Превью внутри</th>
                <th>Ссылка</th>
            </tr>
            {% for item in paginate.items %}
                {% set link = "/publication/admin/edit/" ~ item.getId() %}
                <tr>
                    <td><a href="{{ link }}?lang={{ constant('LANG') }}" class="mini ui icon button"><i
                                    class="icon edit"></i> id = {{ item.getId() }}</a></td>
                    <td><a href="{{ link }}?lang={{ constant('LANG') }}">{{ item.getTitle() }}</a></td>
                    <td>{{ item.getTypeTitle() }}</td>
                    <td>{{ item.getDate() }}</td>
                    <td>{% if item.preview_inner %}<i class="icon checkmark green"></i>{% endif %}</td>
                    {% set url = helper.langUrl(['for':'publication', 'type':item.getTypeSlug(), 'slug':item.getSlug()]) %}
                    <td><a href="{{ url }}" target="_blank">{{ url }}</a></td>
                </tr>
            {% endfor %}
        </table>
{% else %}
    <p>Публикации отсутствуют</p>
{% endif %}

{% if paginate.total_pages > 1 %}
    <div class="pagination">
        {{ partial('main/pagination', ['paginate':paginate, 'url':publicationsLink] ) }}
    </div>
{% endif %}
