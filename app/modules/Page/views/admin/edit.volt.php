<form method="post" class="ui form" action="" enctype="multipart/form-data">

    <!--controls-->
    <div class="ui segment">

        <a href="<?php echo $this->url->get(); ?>page/admin?lang=<?php echo constant('LANG'); ?>" class="ui button">
            <i class="icon left arrow"></i> <?php echo $this->helper->at('Back'); ?>
        </a>

        <div class="ui positive submit button">
            <i class="save icon"></i> <?php echo $this->helper->at('Save'); ?>
        </div>

        <?php if ($model->getId()) { ?>

            <a href="<?php echo $this->url->get(); ?>page/admin/delete/<?php echo $model->getId(); ?>?lang=<?php echo constant('LANG'); ?>" class="ui button red">
                <i class="icon trash"></i> <?php echo $this->helper->at('Delete'); ?>
            </a>

            <?php if ($model->getId()) { ?>
                <a class="ui blue button" target="_blank"
                   href="<?php echo $this->helper->langUrl(array('for' => 'page', 'slug' => $model->getSlug())); ?>">
                    <?php echo $this->helper->at('View Online'); ?>
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

<script type="text/javascript" src="<?php echo $this->url->get(); ?>vendor/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
    tinymce.init({
        selector: "#text",
        language: "en",
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