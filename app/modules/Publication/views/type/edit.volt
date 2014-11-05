<form method="post" class="ui form" action="" enctype="multipart/form-data">

    <!--controls-->
    <div class="ui segment">

        <a href="/publication/type?lang={{ constant('LANG') }}" class="ui button">
            <i class="icon left"></i> Назад
        </a>

        <div class="ui positive submit button">
            <i class="save icon"></i> Сохранить
        </div>

        {% if model.getId() %}

            <a href="/publication/type/add" class="ui button">
                <i class="icon add"></i> Добавить
            </a>

            <a href="/publication/type/delete/{{ model.getId() }}?lang={{ constant('LANG') }}" class="ui button red">
                <i class="icon trash"></i> Удалить
            </a>

            {% if model.getId() %}
                <a class="ui blue button" target="_blank"
                   href="{{ helper.langUrl(['for':'publications','type':model.getSlug()]) }}">
                    Посмотреть раздел на сайте
                </a>
            {% endif %}

        {% endif %}

    </div>
    <!--end controls-->

    <div class="ui segment">
        {{ form.renderDecorated('title') }}
        {{ form.renderDecorated('slug') }}
        {{ form.renderDecorated('limit') }}
        {{ form.renderDecorated('format') }}
        {{ form.renderDecorated('display_date') }}
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
        },
        slug: {
            identifier: 'slug',
            rules: [
                {type: 'empty'}
            ]
        }
    });
</script><!--/end ui semantic-->