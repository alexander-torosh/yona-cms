<p><a href="/admin/admin-user/edit/<?php echo $model->getId(); ?>" class="ui button"><i
                class="icon left"></i> <?php echo $this->helper->translate('Назад'); ?></a></p>


<form method="post" class="ui form" action="">
    <div class="ui segment">
        <?php echo $this->helper->translate('Вы уверены что хотите удалить пользователя <b>%login%</b>?', array('login' => $model->getLogin())); ?>
    </div>
    <input type="submit" class="ui button negative" value="<?php echo $this->helper->translate('Подтвердить удаление'); ?>">
</form>
