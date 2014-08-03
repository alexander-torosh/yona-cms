<p><a href="/admin/admin-user/add" class="ui positive button"><i class="add icon"></i> {{ helper.translate('Add') }}</a></p>

<table class="ui table segment">
    <thead>
        <tr>
            <th>{{ helper.translate('Edit') }}</th>
            <th>{{ helper.translate('Login') }}</th>
            <th>{{ helper.translate('Email') }}</th>
            <th>{{ helper.translate('Active') }}</th>
        </tr>
    </thead>
    <tbody>
        {% for user in entries %}
        <tr>
            <td><a href="/admin/admin-user/edit/{{ user.getId() }}" class="mini ui icon button"><i class="pencil icon"></i></a></td>
            <td>{{ user.getLogin() }}</td>
            <td>{{ user.getEmail() }}</td>
            <td>{% if user.getActive() %}<i class="checkmark icon"></i>{% endif %}</td>
        </tr>
        {% endfor %}
    </tbody>
</table>