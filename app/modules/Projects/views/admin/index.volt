<!--controls-->
<div class="ui segment">

    <a href="/projects/admin/add" class="ui button positive">
        <i class="icon plus"></i> Добавить
    </a>

</div>
<!--/end controls-->

<table class="ui compact table small segment">
    <tr>
        <th></th>
        <th>Сорт.</th>
        <th>Название объекта</th>
        <th>Расположение</th>
        <th>Описание</th>
        <th>Отображается</th>
    </tr>
    {% for item in entries %}
        {% set link = "/projects/admin/edit/" ~ item.getId() %}
        <tr>
            <td><a href="{{ link }}" class="mini ui icon button"><i class="icon edit"></i> id = {{ item.getId() }}</a></td>
            <td>{{ item.getSortorder() }}</td>
            <td><a href="{{ link }}">{{ item.getTitle() }}</a></td>
            <td>{{ item.getLocation() }}</td>
            <td>{{ item.getDescription() }}</td>
            <td>{% if item.visible %}<i class="icon plus"></i>{% endif %}</td>
        </tr>
    {% endfor %}
</table>