<!--controls-->
<div class="ui segment">

    <a href="{{ url.get() }}seo/manager/add" class="ui button positive">
        <i class="icon plus"></i> Add New
    </a>

</div>
<!--/end controls-->

<table class="ui table very compact celled">
    <thead>
    <tr>
        <th style="width: 100px"></th>
        <th>Url</th>
        <th>&lt;title&gt;</th>
    </tr>
    </thead>
    <tbody>
    {% for item in entries %}
        {% set link = url.get() ~ "seo/manager/edit/" ~ item.getId() %}
        <tr>
            <td>
                <a href="{{ link }}" class="mini ui icon button"><i class="icon edit"></i> id = {{ item.getId() }}</a>
            </td>
            <td>{{ item.getUrl() }}</td>
            <td><a href="{{ link }}">{{ item.getHead_title() }}</a></td>
        </tr>
    {% endfor %}
    </tbody>
</table>