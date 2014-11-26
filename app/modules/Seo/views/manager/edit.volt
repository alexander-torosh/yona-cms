<form method="post" class="ui form" action="" enctype="multipart/form-data">

    <!--controls-->
    <div class="ui segment">

        <a href="/seo/manager" class="ui button">
            <i class="icon left arrow"></i> Назад
        </a>

        <div class="ui positive submit button">
            <i class="save icon"></i> Сохранить
        </div>

        {% if model.getId() %}

            <a href="/seo/manager/add" class="ui button">
                <i class="icon add"></i> Добавить
            </a>

            <a href="/seo/manager/delete/{{ model.getId() }}" class="ui button red">
                <i class="icon trash"></i> Удалить
            </a>

        {% endif %}

    </div>
    <!--end controls-->

    <div class="ui segment">
        {{ form.renderDecorated('custom_name') }}

        <div class="ui label" style="text-transform: none;">
            Необходимо использовать Route или Module-Controller-Action. Одновременное указание параметров невозможно
        </div>

        <!--tabs-->
        <div class="ui tabular menu init">
            <a class="item active" data-tab="route">
                Route
            </a>
            <a class="item" data-tab="mca">
                Module-Controller-Action
            </a>
        </div>
        <!--/end tabs-->

        <div class="ui tab active" data-tab="route">
            {{ form.renderDecorated('route') }}
            {{ form.renderDecorated('route_params_json') }}
        </div>

        <div class="ui tab" data-tab="mca">
            {{ form.renderDecorated('module') }}
            {{ form.renderDecorated('controller') }}
            {{ form.renderDecorated('action') }}
        </div>

        {{ form.renderDecorated('language') }}
        {{ form.renderDecorated('query_params_json') }}

        <hr>

        {{ form.renderDecorated('head_title') }}
        {{ form.renderDecorated('meta_description') }}
        {{ form.renderDecorated('meta_keywords') }}
        {{ form.renderDecorated('seo_text') }}
    </div>

</form>

<!--ui semantic-->
<script>
    $('.ui.form').form({
        title: {
            identifier: 'title',
            rules: [
                {type: 'empty'}
            ]
        }
    });
</script><!--/end ui semantic-->