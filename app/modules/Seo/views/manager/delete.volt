<p>
    <a href="/seo/manager/edit/{{ model.getId() }}" class="ui button">
        <i class="icon left"></i> Назад
    </a>
</p>


<form method="post" class="ui form" action="">
    <div class="ui segment">
        <b>{{ model.getCustomName() }} {{ model.getRoute() }}{% if model.getModule() %}
                {{ model.getModule() }}:{{ model.getController() }}:{{ model.getAction() }}
            {% endif %}</b>
    </div>
    <input type="submit" class="ui button negative" value="Удалить">
</form>
