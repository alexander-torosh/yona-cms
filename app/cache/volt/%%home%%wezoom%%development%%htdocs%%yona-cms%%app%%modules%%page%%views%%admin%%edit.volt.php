<form method="post" class="ui form" action="" enctype="multipart/form-data">

    <!--controls-->
    <div class="ui segment">

        <a href="/page/admin" class="ui button">
            <i class="icon left"></i> Назад
        </a>

        <div class="ui positive submit button">
            <i class="save icon"></i> Сохранить
        </div>

        <?php if ($model->getId()) { ?>
        
            <a href="/page/admin/add" class="ui button">
                <i class="icon add"></i> Добавить
            </a>

            <a href="/page/admin/delete/<?php echo $model->getId(); ?>" class="ui button red">
                <i class="icon trash"></i> Удалить
            </a>

            <?php if ($model->getId()) { ?>
                <a class="ui blue button" target="_blank"
                   href="<?php echo $this->url->get(array('for' => 'page', 'slug' => $model->getSlug())); ?>">
                    Посмотреть на сайте
                </a>
            <?php } ?>

        <?php } ?>

    </div>
    <!--end controls-->

    <div class="ui segment">
        <?php echo $form->renderDecorated('title'); ?>
        <?php echo $form->renderDecorated('slug'); ?>
        <?php echo $form->renderDecorated('meta_title'); ?>
        <?php echo $form->renderDecorated('meta_description'); ?>
        <?php echo $form->renderDecorated('meta_keywords'); ?>
        <?php echo $form->renderDecorated('text'); ?>
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

    function elFinderBrowser (field_name, url, type, win) {
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