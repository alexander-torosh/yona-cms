<a class="logo" href="<?php echo $this->helper->langUrl(array('for' => 'index')); ?>">
    <img src="<?php echo $this->url->path(); ?>static/images/logo.png" alt="">
</a>

<!--Github stars-->
<div style="position: absolute;top:10px;left:270px;z-index: 0;height: 30px;overflow: hidden;">
    <iframe frameborder="none" src="http://ghbtns.com/github-btn.html?user=oleksandr-torosh&repo=yona-cms&type=watch&count=true&size=large"></iframe>
</div>
<!--/Github stars-->

<?php echo $this->helper->staticWidget('phone'); ?>

<?php $languages = $this->helper->languages(); ?>
<?php if ($languages->count() > 1) { ?>
    <div class="languages">
        <?php foreach ($languages as $language) { ?>
            <div class="lang">
                <?php echo $this->helper->langSwitcher($language->getIso(), $language->getName()); ?>
            </div>
        <?php } ?>
    </div>
<?php } ?>
