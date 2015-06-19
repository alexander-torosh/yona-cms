<div class="ui segment">
    <a href="{{ url.get() }}seo/manager/edit/{{ model.getId() }}?lang={{ constant('LANG') }}" class="ui button">
        <i class="icon left arrow"></i> Back
    </a>
</div>

<form method="post" class="ui negative message form" action="">
    <p>Delete запись <b>{{ model.getCustomName() }} - {{ model.getRoute() }}{% if model.getModule() %}
        | {{ model.getModule() }}:{{ model.getController() }}:{{ model.getAction() }}{% endif %}</b>?</p>
    <button type="submit" class="ui button negative"><i class="icon trash"></i> Delete</button>
</form>