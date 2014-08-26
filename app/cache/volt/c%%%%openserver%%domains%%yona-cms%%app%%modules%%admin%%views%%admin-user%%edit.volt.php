<p>
    <a href="/admin/admin-user" class="ui button">
        <i class="icon left"></i> <?php echo $this->helper->translate('Back'); ?>
    </a>
    <?php if (isset($model)) { ?>
    <a href="/admin/admin-user/delete/<?php echo $model->getId(); ?>" class="ui button red">
        <i class="icon trash"></i> <?php echo $this->helper->translate('Delete'); ?>
    </a>
    <?php } ?>
</p>

<form method="post" action="" class="ui form segment">
    <?php echo $form->renderDecorated('login'); ?>
    <?php echo $form->renderDecorated('email'); ?>
    <?php echo $form->renderDecorated('password'); ?>
    <?php echo $form->renderDecorated('active'); ?>
    <div class="ui positive submit button">
        <i class="save icon"></i> <?php echo $submitButton; ?>
    </div>
</form>

<script>
    $('.ui.form').form({});
</script>