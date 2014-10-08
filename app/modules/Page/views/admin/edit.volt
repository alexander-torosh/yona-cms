<form method="post" class="ui form" action="" enctype="multipart/form-data">

    <!--controls-->
    <div class="ui segment">

        <a href="/page/admin" class="ui button">
            <i class="icon left"></i> Назад
        </a>

        <div class="ui positive submit button">
            <i class="save icon"></i> Сохранить
        </div>

        {% if model.getId() %}
        
            <a href="/page/admin/add" class="ui button">
                <i class="icon add"></i> Добавить
            </a>

            <a href="/page/admin/delete/{{ model.getId() }}" class="ui button red">
                <i class="icon trash"></i> Удалить
            </a>

            {% if model.getId() %}
                <a class="ui blue button" target="_blank"
                   href="{{ helper.langUrl(['for':'page','slug':model.getSlug()]) }}">
                    Посмотреть на сайте
                </a>
            {% endif %}

        {% endif %}

    </div>
    <!--end controls-->

    <div class="ui segment">
        {{ form.renderDecorated('title') }}
        {{ form.renderDecorated('slug') }}
        {{ form.renderDecorated('meta_title') }}
        {{ form.renderDecorated('meta_description') }}
        {{ form.renderDecorated('meta_keywords') }}
        {{ form.renderDecorated('text') }}
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

<script type="text/javascript" src="/vendor/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
    tinymce.init({
        selector: "#text",
        language: "ru",
        height: "700px",
        plugins: [
            "advlist autolink autosave link image lists charmap print preview hr anchor pagebreak spellchecker",
            "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
            "table contextmenu directionality emoticons textcolor paste textcolor colorpicker textpattern"
        ],

        toolbar1: "newdocument fullpage | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | formatselect fontselect fontsizeselect",
        toolbar2: "cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor image media code | insertdatetime preview | forecolor backcolor",
        toolbar3: "table | hr removeformat | subscript superscript | charmap emoticons | print fullscreen | ltr rtl | spellchecker | visualchars visualblocks nonbreaking template pagebreak restoredraft",

        menubar: false,
        toolbar_items_size: 'small',

        fontsize_formats: "8px 9px 10px 11px 12px 13px 14px 15px 16px 17px 18px",
        file_browser_callback : elFinderBrowser
    });

</script>