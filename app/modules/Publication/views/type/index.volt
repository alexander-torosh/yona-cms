<!--controls-->
<div class="ui segment">

    <a href="/publication/admin?lang={{ constant('LANG') }}" class="ui button">
        <i class="icon left"></i> Перечень публикаций
    </a>

    <a href="/publication/type/add" class="ui button positive">
        <i class="icon plus"></i> Добавить
    </a>

</div>
<!--/end controls-->

<table class="ui compact table small segment">
    <tr>
        <th style="width: 100px"></th>
        <th>Название</th>
        <th>URL раздела</th>
        <th>Формат вывода</th>
        <th>Отображать дату</th>
    </tr>
    {% for item in entries %}
        {% set link = "/publication/type/edit/" ~ item.getId() %}
        <tr>
            <td><a href="{{ link }}?lang={{ constant('LANG') }}" class="mini ui icon button"><i class="icon edit"></i> id = {{ item.getId() }}</a></td>
            <td><a href="{{ link }}?lang={{ constant('LANG') }}">{{ item.getTitle() }}</a></td>

            {% set pub_link = helper.langUrl(['for':'publications', 'type': item.getSlug()]) %}
            <td><a href="{{ pub_link }}" target="_blank">{{ pub_link }}</a></td>
            <td>{{ item.getFormatTitle() }}</td>
            <td>{% if item.getDisplay_date() %}<i class="icon checkmark green"></i>{% endif %}</td>
        </tr>
    {% endfor %}
</table>