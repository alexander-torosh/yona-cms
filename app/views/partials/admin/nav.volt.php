<div class="ui black inverted tiered menu">
    <a class="item<?php echo $this->helper->activeMenu()->activeClass('admin-home'); ?>" href="<?php echo $this->url->get(array('for' => 'admin')); ?>">
        <i class="browser icon"></i> <?php echo $this->helper->at('Admin Dashboard'); ?>
    </a>

    <div class="ui dropdown item">
        <i class="font icon"></i> <?php echo $this->helper->at('Contents'); ?> <i class="icon dropdown"></i>

        <div class="menu">
            <a class="item<?php echo $this->helper->activeMenu()->activeClass('admin-page'); ?>" href="<?php echo $this->url->get(); ?>page/admin">
                <i class="file outline icon"></i> <?php echo $this->helper->at('Manage Pages'); ?>
            </a>
            <a class="item<?php echo $this->helper->activeMenu()->activeClass('admin-publication'); ?>"
               href="<?php echo $this->url->get(); ?>publication/admin">
                <i class="calendar icon"></i> <?php echo $this->helper->at('Manage Publication'); ?>
            </a>
            <a class="item<?php echo $this->helper->activeMenu()->activeClass('admin-widget'); ?>" href="<?php echo $this->url->get(); ?>widget/admin">
                <i class="text file icon"></i> <?php echo $this->helper->at('Manage Widget'); ?>
            </a>
            <a class="item<?php echo $this->helper->activeMenu()->activeClass('tree'); ?>" href="<?php echo $this->url->get(); ?>tree/admin">
                <i class="tree icon"></i> <?php echo $this->helper->at('Tree Categories'); ?>
            </a>
            <a class="item<?php echo $this->helper->activeMenu()->activeClass('admin-fm'); ?>" href="<?php echo $this->url->get(); ?>file-manager">
                <i class="file image outline icon"></i> <?php echo $this->helper->at('File Manager'); ?>
            </a>
        </div>
    </div>
    <div class="ui dropdown item">
        <i class="lab icon"></i> SEO <i class="icon dropdown"></i>

        <div class="menu">
            <a class="item<?php echo $this->helper->activeMenu()->activeClass('seo-robots'); ?>" href="<?php echo $this->url->get(); ?>seo/robots">
                <i class="android icon"></i> Robots.txt
            </a>
            <a class="item<?php echo $this->helper->activeMenu()->activeClass('seo-sitemap'); ?>" href="<?php echo $this->url->get(); ?>seo/sitemap">
                <i class="sitemap icon"></i> Sitemap.xml
            </a>
            <a class="item<?php echo $this->helper->activeMenu()->activeClass('seo-manager'); ?>" href="<?php echo $this->url->get(); ?>seo/manager">
                <i class="lightbulb icon"></i> SEO Manager
            </a>
        </div>
    </div>
    <div class="ui dropdown item">
        <i class="wrench icon"></i> <?php echo $this->helper->at('Admin'); ?> <i class="icon dropdown"></i>

        <div class="menu">
            <a class="item<?php echo $this->helper->activeMenu()->activeClass('admin-user'); ?>" href="<?php echo $this->url->get(); ?>admin/admin-user">
                <i class="user icon"></i> <?php echo $this->helper->at('Manage Users'); ?>
            </a>
            <a class="item<?php echo $this->helper->activeMenu()->activeClass('admin-cms'); ?>" href="<?php echo $this->url->get(); ?>cms/configuration">
                <i class="settings icon"></i> <?php echo $this->helper->at('CMS Configuration'); ?>
            </a>
            <a class="item<?php echo $this->helper->activeMenu()->activeClass('admin-language'); ?>" href="<?php echo $this->url->get(); ?>cms/language">
                <i class="globe icon"></i> <?php echo $this->helper->at('Languages'); ?>
            </a>
            <a class="item<?php echo $this->helper->activeMenu()->activeClass('admin-translate'); ?>" href="<?php echo $this->url->get(); ?>cms/translate">
                <i class="book icon"></i> <?php echo $this->helper->at('Translate'); ?>
            </a>
            <a class="item<?php echo $this->helper->activeMenu()->activeClass('admin-javascript'); ?>"
               href="<?php echo $this->url->get(); ?>cms/javascript">
                <i class="code icon"></i> <?php echo $this->escaper->escapeHtml('<head>, <body> javascript'); ?>
            </a>
        </div>
    </div>
    <a href="javascript:void(0);" class="item right" onclick="document.getElementById('logout-form').submit()">
        <i class="plane icon"></i> <?php echo $this->helper->at('Logout'); ?>
    </a>

    <form action="<?php echo $this->url->get(); ?>admin/index/logout" method="post" style="display: none;" id="logout-form">
        <input type="hidden" name="<?php echo $this->security->getTokenKey(); ?>"
               value="<?php echo $this->security->getToken(); ?>">
    </form>
    <a href="<?php echo $this->url->get(); ?>" class="item right" target="_blank">
        <i class="home icon"></i> <?php echo $this->helper->at('View Site'); ?>
    </a>
</div>