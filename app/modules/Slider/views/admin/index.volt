<!--controls-->
<div class="ui segment">

    <a href="/slider/admin/add" class="ui button positive">
        <i class="icon plus"></i> Add New
    </a>

</div>
<!--/end controls-->

<table class="ui table very compact celled">
    <thead>
    <tr>
        <th></th>
        <th>ID</th>
        <th>Name</th>
        <th>Duration</th>
        <th>Duration of transition to next slide</th>
        <th>Is visible</th>
    </tr>
    </thead>
    <tbody>
    {% for item in entries %}
        {% set link = "/slider/admin/edit/" ~ item.getId() %}
        <tr>
            <td>
                <a href="{{ link }}" class="mini ui icon button"><i class="icon edit"></i> edit</a>
            </td>
            <td><a href="{{ link }}">{{ item.getId() }}</a></td>
            <td><a href="{{ link }}">{{ item.getTitle() }}</a></td>
            <td>{{ item.getAnimationSpeed() }}</td>
            <td>{{ item.getDelay() }}</td>
            <td>{% if item.visible %}<i class="icon plus"></i>{% else %}<i class="icon minus"></i>{% endif %}</td>
        </tr>
    {% endfor %}
    </tbody>
</table>