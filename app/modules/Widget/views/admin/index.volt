<div class="ui segment">
    <a href="{{ url.get() }}widget/admin/add" class="ui positive button"><i
                class="add icon"></i> {{ helper.at('Add New') }}</a>
</div>

<table class="ui table very compact celled">
    <thead>
    <tr>
        <th style="width:25%">{{ helper.at('ID') }}</th>
        <th>{{ helper.at('Title') }}</th>
    </tr>
    </thead>
    <tbody>
    {% for item in entries %}
        {% set link = url.get() ~ 'widget/admin/edit/' ~ item.getId() %}
        <tr>
            <td><a href="{{ link }}">{{ item.getId() }}</a></td>
            <td><a href="{{ link }}">{{ item.getTitle() }}</a></td>
        </tr>
    {% endfor %}
    </tbody>
</table>