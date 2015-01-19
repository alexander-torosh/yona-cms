<!--controls-->
<div class="ui segment">

    <a href="/cms/language/add" class="ui button positive">
        <i class="icon plus"></i> Add New
    </a>

</div>
<!--/end controls-->

<table class="ui table very compact celled">
    <thead>
    <tr>
        <th>Name</th>
        <th>Code</th>
        <th>ISO</th>
        <th>Locale</th>
        <th>URL</th>
        <th>Sort order</th>
        <th>Main</th>
    </tr>
    </thead>
    <tbody>
    {% for item in entries %}
        <tr>
            <td><a href="/cms/language/edit/{{ item.getId() }}">{{ item.getName() }}</a></td>
            <td>{{ item.getShort_name() }}</td>
            <td>{{ item.getIso() }}</td>
            <td>{{ item.getLocale() }}</td>

            {% set url = '/' %}
            {% if item.getUrl() and not item.getPrimary() %}{% set url = '/' ~ item.getUrl() ~ '/' %}{% endif %}
            <td><a href="{{ url }}" target="_blank">{{ url }}</a></td>
            <td>{{ item.getSortorder() }}</td>
            <td>{% if item.getPrimary() %}<i class="icon plus"></i>{% endif %}</td>
        </tr>
    {% endfor %}
    </tbody>
</table>