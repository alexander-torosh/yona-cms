<div class="ui black inverted tiered menu">
    <a class="item<?php echo $this->helper->activeMenu()->activeClass('admin-home'); ?>" href="<?php echo $this->url->get(array('for' => 'admin')); ?>">
        <i class="browser icon"></i> Админ-панель
    </a>
    <div class="ui dropdown item">
        <i class="font icon"></i> Контент <i class="icon dropdown"></i>
        <div class="menu">
            <a class="item<?php echo $this->helper->activeMenu()->activeClass('admin-page'); ?>" href="/page/admin">
                <i class="file outline icon"></i> Статические страницы
            </a>
            <a class="item<?php echo $this->helper->activeMenu()->activeClass('admin-publication'); ?>" href="/publication/admin">
                <i class="calendar icon"></i> Публикации
            </a>
            <a class="item<?php echo $this->helper->activeMenu()->activeClass('admin-projects'); ?>" href="/projects/admin">
                <i class="home icon"></i> Проекты
            </a>
            <a class="item<?php echo $this->helper->activeMenu()->activeClass('admin-video'); ?>" href="/video/admin">
                <i class="video icon"></i> Видео
            </a>
            <a class="item<?php echo $this->helper->activeMenu()->activeClass('admin-widget'); ?>" href="/widget/admin">
                <i class="code icon"></i> Виджеты
            </a>
            <a class="item<?php echo $this->helper->activeMenu()->activeClass('admin-fm'); ?>" href="/file-manager">
                <i class="attachment icon"></i> Файловый менеджер
            </a>
        </div>
    </div>
    <div class="ui dropdown item">
        <i class="settings icon"></i> Администрирование <i class="icon dropdown"></i>
        <div class="menu">
            <a class="item<?php echo $this->helper->activeMenu()->activeClass('admin-user'); ?>" href="/admin/admin-user">
                <i class="user icon"></i> Администраторы
            </a>
            <a class="item<?php echo $this->helper->activeMenu()->activeClass('admin-cms'); ?>" href="/cms/configuration">
                <i class="setting icon"></i> Конфигурация
            </a>
        </div>
    </div>
    <a href="/admin/index/logout" class="item right">
        <i class="plane icon"></i> Вылет
    </a>
    <a href="/" class="item right" target="_blank">
        <i class="home icon"></i> На сайт
    </a>
</div>