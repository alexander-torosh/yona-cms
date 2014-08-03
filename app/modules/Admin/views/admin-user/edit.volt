<p>
    <a href="/admin/admin-user" class="ui button">
        <i class="icon left"></i> {{ helper.translate('Back') }}
    </a>
    {% if model is defined %}
    <a href="/admin/admin-user/delete/{{ model.getId() }}" class="ui button red">
        <i class="icon trash"></i> {{ helper.translate('Delete') }}
    </a>
    {% endif %}
</p>

<form method="post" action="" class="ui form segment">
    {{ form.renderDecorated('login') }}
    {{ form.renderDecorated('email') }}
    {{ form.renderDecorated('password') }}
    {{ form.renderDecorated('active') }}
    <div class="ui positive submit button">
        <i class="save icon"></i> {{ submitButton }}
    </div>
</form>

<script>
    $('.ui.form').form({});
</script>