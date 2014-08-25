<form method="post" class="ui form" action="" enctype="multipart/form-data">

    <!--controls-->
    <div class="ui segment">

        <a href="/projects/admin" class="ui button">
            <i class="icon left"></i> Назад
        </a>

        <div class="ui positive submit button">
            <i class="save icon"></i> Сохранить
        </div>

        {% if model.getId() %}

            <a href="/projects/admin/delete/{{ model.getId() }}" class="ui button red">
                <i class="icon trash"></i> Удалить
            </a>

            {% if model.getId() %}
                <a class="ui blue button"
                   href="/project/{{ model.getId() }}">
                    Посмотреть на сайте
                </a>
            {% endif %}

        {% endif %}

    </div>
    <!--end controls-->

    <div class="ui segment">
        {{ form.renderDecorated('title') }}
        {{ form.renderDecorated('location') }}
        {{ form.renderDecorated('description') }}
        {{ form.renderDecorated('visible') }}
        {{ form.renderDecorated('sortorder') }}

        <!--images-->
        <div class="field">
            Добавить фото<br>
            {{ form.render('image') }}
        </div>
        {% for image in model.getProjectImages() %}
            <div class="ui image">
                {% set image = helper.image([
                'id': image.getId(),
                'type': 'project',
                'width': 200,
                'hash': true
                ]) %}
                {{ image.imageHtml() }}
            </div>
        {% endfor %}
        <!--/end images-->

        <input type="hidden" name="form" value="1">
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
        location: {
            identifier: 'location',
            rules: [
                {type: 'empty'}
            ]
        }
    });
</script><!--/end ui semantic-->

<script type="text/javascript" src="/vendor/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
    tinymce.init({
        selector: "textarea",
        language: 'ru',
        plugins: [
            "advlist autolink lists link image charmap print preview anchor",
            "searchreplace visualblocks code fullscreen",
            "insertdatetime media table contextmenu paste moxiemanager"
        ],
        toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
    });
</script>