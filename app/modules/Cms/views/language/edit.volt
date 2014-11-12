<form method="post" class="ui form" action="" enctype="multipart/form-data">

    <!--controls-->
    <div class="ui segment">

        <a href="/cms/language" class="ui button">
            <i class="icon left"></i> Назад
        </a>

        <div class="ui positive submit button">
            <i class="save icon"></i> Сохранить
        </div>

        {% if model.getId() %}

            <a href="/cms/language/add" class="ui button">
                <i class="icon add"></i> Добавить
            </a>

            <a href="/cms/language/delete/{{ model.getId() }}" class="ui button red">
                <i class="icon trash"></i> Удалить
            </a>

        {% endif %}

    </div>
    <!--end controls-->

    <div class="ui segment">
        {{ form.renderDecorated('iso') }}
        {{ form.renderDecorated('locale') }}
        {{ form.renderDecorated('name') }}
        {{ form.renderDecorated('short_name') }}
        {{ form.renderDecorated('url') }}
        {% if model.getId() %}
            {{ form.renderDecorated('sortorder') }}
            {{ form.renderDecorated('primary') }}
        {% endif %}
    </div>

</form>

<!--ui semantic-->
<script>
    $('.ui.form').form({
        iso: {
            identifier: 'iso',
            rules: [
                {type: 'empty'}
            ]
        },
        name: {
            identifier: 'name',
            rules: [
                {type: 'empty'}
            ]
        }
    });
</script><!--/end ui semantic-->