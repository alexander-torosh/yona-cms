<!--controls-->
<div class="ui segment">

    <a href="/publication/admin/{{ type }}/add" class="ui button positive">
        <i class="icon plus"></i> Add New
    </a>

    <a href="/publication/type" class="ui button">
        <i class="icon list"></i> Типы публикаций
    </a>

</div>
<!--/end controls-->

<div class="ui tabular menu">
    <a href="/publication/admin?lang={{ constant('LANG') }}" class="item{% if not type_id%} active{% endif %}">Все</a>
    {% for type_el in types %}
    <a href="{{ url(['for':'publications_admin','type':type_el.getSlug()]) }}?lang={{ constant('LANG') }}" class="item{% if type_el.getId() == type_id%} active{% endif %}">
        {{ type_el.getTitle() }}
    </a>
    {% endfor %}
</div>

{% if paginate.total_items > 0 %}

        <table class="ui table very compact celled">
            <thead>
            <tr>
                <th style="width: 100px"></th>
                <th>Title</th>
                <th>Type of Publication</th>
                <th>Created Date</th>
                <th>Thumbs Inside</th>
                <th>Url</th>
            </tr>
            </thead>
            <tbody>
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
            </tbody>
        </table>
{% else %}
    <p>No publication</p>
{% endif %}

{% if paginate.total_pages > 1 %}
    <div class="pagination">
        {{ partial('admin/pagination', ['paginate':paginate] ) }}
    </div>
{% endif %}
