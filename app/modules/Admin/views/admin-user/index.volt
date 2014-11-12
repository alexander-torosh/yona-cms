<p><a href="/admin/admin-user/add" class="ui positive button"><i class="add icon"></i> Добавить</a></p>

<table class="ui table segment">
    <thead>
        <tr>
            <th style="width: 100px"></th>
            <th>Логин</th>
            <th>Email</th>
            <th>Активен</th>
        </tr>
    </thead>
    <tbody>
        {% for user in entries %}
        <tr>
            {% set url = '/admin/admin-user/edit/' ~ user.getId() %}
            <td><a href="{{ url }}" class="mini ui icon button"><i class="pencil icon"></i></a></td>
            <td><a href="{{ url }}">{{ user.getLogin() }}</a></td>
            <td>{{ user.getEmail() }}</td>
            <td>{% if user.getActive() %}<i class="icon checkmark grren"></i>{% endif %}</td>
        </tr>
        {% endfor %}
    </tbody>
</table>