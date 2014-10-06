<!--controls-->
<div class="ui segment">

    <a href="/cms/language/add" class="ui button positive">
        <i class="icon plus"></i> Добавить
    </a>

</div>
<!--/end controls-->

<table class="ui table segment">
    <tr>
        <th>Имя</th>
        <th>ISO</th>
        <th>URL</th>
        <th>Порядковый №</th>
        <th>Основной</th>
    </tr>
    {% for item in entries %}
        <tr>
            <td><a href="/cms/language/edit/{{ item.getId() }}">{{ item.getName() }}</a></td>
            <td>{{ item.getIso() }}</td>
            <td>{{ item.getUrl() }}</td>
            <td>{{ item.getSortorder() }}</td>
            <td>{% if item.getPrimary() %}<i class="icon plus"></i>{% endif %}</td>
        </tr>
    {% endfor %}
</table>