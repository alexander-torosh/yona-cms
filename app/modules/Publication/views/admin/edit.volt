<form method="post" class="ui form" action="" enctype="multipart/form-data">

    <!--controls-->
    <div class="ui segment">

        {% set typeBackUrl = ((type is defined) ? '/' ~ type : '') %}
        <a href="{{ url.get() }}publication/admin{{ typeBackUrl }}?lang={{ constant('LANG') }}" class="ui button">
            <i class="icon left arrow"></i> {{ helper.at('Back') }}
        </a>

        <div class="ui positive submit button">
            <i class="save icon"></i> {{ helper.at('Save') }}
        </div>

        {% if model.getId() %}

            <a href="{{ url.get() }}publication/admin/delete/{{ model.getId() }}?lang={{ constant('LANG') }}" class="ui button red">
                <i class="icon trash"></i> {{ helper.at('Delete') }}
            </a>

            {% if model.getId() %}
                <a class="ui blue button" target="_blank"
                   href="{{ helper.langUrl(['for':'publication','type':model.getTypeSlug(), 'slug':model.getSlug()]) }}">
                    {{ helper.at('View Online') }}
                </a>
            {% endif %}

        {% endif %}

    </div>
    <!--end controls-->

    <div class="ui segment">
        <div class="ui grid">
            <div class="twelve wide column">
                {{ form.renderDecorated('title') }}
                {{ form.renderDecorated('slug') }}
                {{ form.renderDecorated('meta_title') }}
                {{ form.renderDecorated('meta_description') }}
                {{ form.renderDecorated('meta_keywords') }}
                {{ form.renderDecorated('text') }}
            </div>
            <div class="four wide column">
                {{ form.renderDecorated('type_id') }}
                {{ form.renderDecorated('date') }}
                {{ form.renderDecorated('preview_src') }}
                {{ form.renderDecorated('preview_inner') }}
            </div>
        </div>
    </div>

</form>

<!--ui semantic-->
<script>
    $( ".ui.form" ).form({
        fields: {
            title: {
                identifier: 'title',
                rules: [
                    {type: 'empty'}
                ]
            }
        }
    });
</script><!--/end ui semantic-->

<link rel="stylesheet" href="{{ url.path() }}vendor/bootstrap/datetimepicker/bootstrap-datetimepicker.min.css">
<script src="{{ url.path() }}vendor/bootstrap/datetimepicker/moment.js"></script>
<script src="{{ url.path() }}vendor/bootstrap/datetimepicker/bootstrap-datetimepicker.min.js"></script>
<script>
    $('#date').datetimepicker({
        locale: 'en',
        format: 'YYYY-MM-DD HH:mm:ss',
        showClose: true
    });
</script>

<script type="text/javascript" src="{{ url.get() }}vendor/tiny_mce_3/tiny_mce.js"></script>
<script type="text/javascript">
    tinyMCE.init({
        // General options
        selector : "#text",
        language: "en", // "ru"
        height: "500px",
        theme : "advanced",
        plugins : "autolink,lists,spellchecker,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template",

        // Theme options
        theme_advanced_buttons1 : ",bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,formatselect,fontsizeselect,|,forecolor,backcolor",
        theme_advanced_buttons2 : "pastetext,pasteword,|,bullist,numlist,|,outdent,indent,|,undo,redo,|,link,unlink,anchor,image,cleanup,code,|,charmap,iespell,media,advhr,",
        theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,fullscreen",
        theme_advanced_toolbar_location : "top",
        theme_advanced_toolbar_align : "left",
        theme_advanced_statusbar_location : "bottom",
        theme_advanced_resizing : true,
        theme_advanced_blockformats : "p,h1,h2,h3,h4",
        theme_advanced_font_sizes: "9px,10px,11px,12px,13px,14px,15px,16px,17px,18px,19px,20px",

        browser_spellcheck : true,

        relative_urls : false,
        convert_urls : true,

        element_format : "html5",

        file_browser_callback : 'elFinderBrowser_3',

        // Skin options
        skin : "o2k7",
        skin_variant : "silver",

        // Example content CSS (should be your site CSS)
        content_css : "{{ url.get() }}static/css/tinymce.css"
    });
</script>
