<form method="post" class="ui form" action="" enctype="multipart/form-data">
    <input type="hidden" name="form" value="1">

    <!--controls-->
    <div class="ui segment">

        <a href="/publication/admin" class="ui button">
            <i class="icon left"></i> Назад
        </a>

        <div class="ui positive submit button">
            <i class="save icon"></i> Сохранить
        </div>

        {% if model.getId() %}

            <a href="/publication/admin/add" class="ui button">
                <i class="icon add"></i> Добавить
            </a>

            <a href="/publication/admin/delete/{{ model.getId() }}" class="ui button red">
                <i class="icon trash"></i> Удалить
            </a>

            {% if model.getId() %}
                <a class="ui blue button" target="_blank"
                   href="{{ url(['for':'publication','type':model.getType(), 'slug':model.getSlug()]) }}">
                    Посмотреть на сайте
                </a>
            {% endif %}

        {% endif %}

    </div>
    <!--end controls-->

    <div class="ui segment">
        {{ form.renderDecorated('type') }}
        {{ form.renderDecorated('title') }}
        {{ form.renderDecorated('slug') }}
        {{ form.renderDecorated('date') }}

        <!--image-->
        <div class="field">
            Загрузить превью<br>
            {{ form.render('image') }}
        </div>
        {% set image = helper.image([
        'id': model.getId(),
        'type': 'publication',
        'width': 200,
        'hash': true
        ]) %}
        {% if image.isExists() %}
            <div class="ui image" style="margin-bottom:20px;">
                {{ image.imageHtml() }}
            </div>
            {{ form.renderDecorated('preview_inner') }}
        {% endif %}
        <!--/end image-->

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

<link rel="stylesheet" href="/vendor/pickadate/themes/classic.css">
<link rel="stylesheet" href="/vendor/pickadate/themes/classic.date.css">
<script src="/vendor/pickadate/picker.js"></script>
<script src="/vendor/pickadate/picker.date.js"></script>
<script>
    $(function () {
        $.extend($.fn.pickadate.defaults, {
            monthsFull: [ 'Января', 'Февраля', 'Марта', 'Апреля', 'Мая', 'Июня', 'Июля', 'Августа', 'Сентября', 'Октября', 'Ноября', 'Декабря' ],
            monthsShort: [ 'Янв', 'Фев', 'Мар', 'Апр', 'Май', 'Июн', 'Июл', 'Авг', 'Сен', 'Окт', 'Ноя', 'Дек' ],
            weekdaysFull: [ 'воскресенье', 'понедельник', 'вторник', 'среда', 'четверг', 'пятница', 'суббота' ],
            weekdaysShort: [ 'вс', 'пн', 'вт', 'ср', 'чт', 'пт', 'сб' ],
            today: 'сегодня',
            clear: 'очистить',
            close: 'закрыть',
            firstDay: 1,
            format: 'yyyy-mm-dd',
            formatSubmit: 'yyyy-mm-dd'
        })

        $("#date").pickadate({

        });
    });
</script>

<script type="text/javascript" src="/vendor/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
    tinymce.init({
        selector: "#text",
        language: "ru",
        height: "700px",
        theme: "modern",
        plugins: [
            "advlist autolink autosave link image lists charmap print preview hr anchor pagebreak spellchecker",
            "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
            "table contextmenu directionality emoticons textcolor paste textcolor colorpicker textpattern"
        ],

        toolbar1: "bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | formatselect fontselect fontsizeselect",
        toolbar2: "cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor image media code | insertdatetime preview | forecolor backcolor",
        toolbar3: "table | hr removeformat | subscript superscript | charmap emoticons | print fullscreen | ltr rtl | spellchecker | visualchars visualblocks nonbreaking template publicationbreak restoredraft",

        menubar: true,
        toolbar_items_size: 'small',
        image_advtab: true,

        fontsize_formats: "8px 9px 10px 11px 12px 13px 14px 15px 16px 17px 18px",

        file_browser_callback: elFinderBrowser
    });

    function elFinderBrowser(field_name, url, type, win) {
        tinymce.activeEditor.windowManager.open({
            file: '/vendor/elfinder-2.1/elfinder_tinymce.html',// use an absolute path!
            title: 'elFinder 2.0',
            width: 900,
            height: 450,
            resizable: 'yes'
        }, {
            setUrl: function (url) {
                console.log(url);
                win.document.getElementById(field_name).value = url.url;
            }
        });
        return false;
    }

</script>