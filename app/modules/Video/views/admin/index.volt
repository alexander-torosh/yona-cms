<!--controls-->
<div class="ui segment">

    <a href="/video/admin/add" class="ui button positive">
        <i class="icon plus"></i> Добавить
    </a>

</div>
<!--/end controls-->

<table class="ui compact table small segment">
    <tr>
        <th></th>
        <th>Сорт.</th>
        <th>Название</th>
    </tr>
    {% for item in entries %}
        {% set link = "/video/admin/edit/" ~ item.getId() %}
        <tr>
            <td><a href="{{ link }}" class="mini ui icon button"><i class="icon edit"></i> id = {{ item.getId() }}</a></td>
            <td>{{ item.getSortorder() }}</td>
            <td><a href="{{ link }}">{{ item.getTitle() }}</a></td>
        </tr>
    {% endfor %}
</table>