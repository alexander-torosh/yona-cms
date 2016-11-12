<!--controls-->
<div class="ui segment">

    <a href="{{ url.get() }}publication/admin/{{ type is not empty ? type ~ '/' : '' }}/add" class="ui button positive">
        <i class="icon plus"></i> {{ helper.at('Add New') }}
    </a>

    <a href="{{ url.get() }}publication/type" class="ui button">
        <i class="icon list"></i> {{ helper.at('Publications types') }}
    </a>

</div>
<!--/end controls-->

<div class="ui tabular menu">
    <a href="{{ url.get() }}publication/admin?lang={{ constant('LANG') }}"
       class="item{% if not type_id %} active{% endif %}">{{ helper.at('All') }}</a>
    {% for type_el in types %}
        <a href="{{ url(['for':'publications_admin','type':type_el.getSlug()]) }}?lang={{ constant('LANG') }}"
           class="item{% if type_el.getId() == type_id %} active{% endif %}">
            {{ type_el.getTitle() }}
        </a>
    {% endfor %}
</div>

{% if paginate.total_items > 0 %}

    <table class="ui table very compact celled">
        <thead>
        <tr>
            <th style="width: 100px"></th>
            <th>{{ helper.at('Title') }}</th>
            <th style="width: 50px;">{{ helper.at('Image') }}</th>
            <th>{{ helper.at('Type of Publication') }}</th>
            <th style="width: 150px">{{ helper.at('Publication Date') }}</th>
            <th>{{ helper.at('Thumbs Inside') }}</th>
            <th>{{ helper.at('Url') }}</th>
        </tr>
        </thead>
        <tbody>
        {% for item in paginate.items %}
            {% set link = url.get() ~ "publication/admin/edit/" ~ item.getId() %}
            {% set image = helper.image(['id':item.getId(),'type':'publication','width':50]) %}
            <tr>
                <td><a href="{{ link }}?lang={{ constant('LANG') }}" class="mini ui icon button"><i
                                class="icon edit"></i> id = {{ item.getId() }}</a></td>
                <td><a href="{{ link }}?lang={{ constant('LANG') }}">{{ item.getTitle() }}</a></td>
                <td><a href="{{ link }}?lang={{ constant('LANG') }}">{% if image.isExists() %}{{ image.imageHTML() }}{% endif %}</a></td>
                <td>{{ item.getTypeTitle() }}</td>
                <td>{{ item.getDate() }}</td>
                <td>{% if item.getPreviewInner() %}<i class="icon checkmark green"></i>{% endif %}</td>
                {% set url = helper.langUrl(['for':'publication', 'type':item.getTypeSlug(), 'slug':item.getSlug()]) %}
                <td><a href="{{ url }}" target="_blank">{{ url }}</a></td>
            </tr>
        {% endfor %}
        </tbody>
    </table>
{% else %}
    <p>{{ helper.at('Entries not found') }}</p>
{% endif %}

{% if paginate.total_pages > 1 %}
    <div class="pagination">
        {{ partial('admin/pagination', ['paginate':paginate] ) }}
    </div>
{% endif %}
