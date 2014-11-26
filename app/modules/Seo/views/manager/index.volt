<!--controls-->
<div class="ui segment">

    <a href="/seo/manager/add" class="ui button positive">
        <i class="icon plus"></i> Добавить
    </a>

</div>
<!--/end controls-->

<table class="ui table very compact celled">
    <thead>
    <tr>
        <th style="width: 100px"></th>
        <th>Название</th>
        <th>Route</th>
        <th>M-C-A</th>
        <th>Язык</th>
        <th>Route Params</th>
        <th>GET Params</th>
    </tr>
    </thead>
    <tbody>
    {% for item in entries %}
        {% set link = "/seo/manager/edit/" ~ item.getId() %}
        <tr>
            <td><a href="{{ link }}" class="mini ui icon button"><i class="icon edit"></i> id = {{ item.getId() }}</a>
            </td>
            <td><a href="{{ link }}">{{ item.getCustomName() }}</a></td>
            <td>{{ item.getRoute() }}</td>
            <td>{% if item.getModule() %}
                    {{ item.getModule() }}:{{ item.getController() }}:{{ item.getAction() }}
                {% endif %}
            </td>
            <td>{{ item.getLanguage() }}</td>
            <td>{{ item.getRouteParamsJson() }}</td>
            <td>{{ item.getQueryParamsJson() }}</td>
        </tr>
    {% endfor %}
    </tbody>
</table>