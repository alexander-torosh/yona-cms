<!--controls-->
<div class="ui segment">

    <a href="/publication/admin/add" class="ui button positive">
        <i class="icon plus"></i> Добавить
    </a>

</div>
<!--/end controls-->

<table class="ui compact table small segment">
    <tr>
        <th style="width: 100px"></th>
        <th>Название</th>
        <th>Тип публикации</th>
        <th>Дата публикации</th>
        <th>Превью внутри</th>
        <th>Ссылка</th>
    </tr>
    {% for item in entries %}
        {% set link = "/publication/admin/edit/" ~ item.getId() %}
        <tr>
            <td><a href="{{ link }}" class="mini ui icon button"><i class="icon edit"></i> id = {{ item.getId() }}</a></td>
            <td><a href="{{ link }}">{{ item.getTitle() }}</a></td>
            <td>{{ item.getTypeTitle() }}</td>
            <td>{{ item.getDate() }}</td>
            <td>{% if item.preview_inner %}<i class="icon checkmark green"></i>{% endif %}</td>
            {% set url = helper.langUrl(['for':'publication', 'type':item.getType(), 'slug':item.getSlug()]) %}
            <td><a href="{{ url }}" target="_blank">{{ url }}</a></td>
        </tr>
    {% endfor %}
</table>