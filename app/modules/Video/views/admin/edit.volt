<form method="post" class="ui form" action="" enctype="multipart/form-data">

    <!--controls-->
    <div class="ui segment">

        <a href="/video/admin" class="ui button">
            <i class="icon left"></i> Назад
        </a>

        <div class="ui positive submit button">
            <i class="save icon"></i> Сохранить
        </div>

        {% if model.getId() %}
        
            <a href="/video/admin/add" class="ui button">
                <i class="icon add"></i> Добавить
            </a>

            <a href="/video/admin/delete/{{ model.getId() }}" class="ui button red">
                <i class="icon trash"></i> Удалить
            </a>

            {% if model.getId() %}
                <a class="ui blue button"
                   href="/video/{{ model.getId() }}">
                    Посмотреть на сайте
                </a>
            {% endif %}

        {% endif %}

    </div>
    <!--end controls-->

    <div class="ui segment">
        {{ form.renderDecorated('title') }}
        {{ form.renderDecorated('youtube_link') }}
        {{ form.renderDecorated('sortorder') }}

        <input type="hidden" name="form" value="1">
    </div>

</form>

{% if model.getId() %}
<div class="ui segment">
    <img alt="" width="200" src="{{ model.getYoutubeImageSrc() }}"><br><br>
    <iframe width="560" height="315" src="//www.youtube.com/embed/{{ model.getYoutubeHash() }}?rel=0" frameborder="0" allowfullscreen></iframe>
</div>
{% endif %}

<!--ui semantic-->
<script>
    $('.ui.form').form({
        title: {
            identifier: 'title',
            rules: [
                {type: 'empty'}
            ]
        },
        youtube_link: {
            identifier: 'youtube_link',
            rules: [
                {type: 'empty'}
            ]
        }
    });
</script><!--/end ui semantic-->