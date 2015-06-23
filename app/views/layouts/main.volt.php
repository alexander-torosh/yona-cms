<div class="wrapper-in">

    <header>
        <?php echo $this->partial('main/header'); ?>
    </header>

    <?php echo $this->partial('main/menu'); ?>

    <div id="main">

        <?php echo $this->getContent(); ?>

        <?php if (isset($seo_text) && !isset($seo_text_inner)) { ?>
            <div class="seo-text">
                <?php echo $seo_text; ?>
            </div>
        <?php } ?>

    </div>

    <footer>
        <?php echo $this->partial('main/footer'); ?>
    </footer>

</div>

<?php if ($this->registry->cms['PROFILER']) { ?>
    <?php echo $this->helper->dbProfiler(); ?>
<?php } ?>

<?php echo $this->helper->javascript('body'); ?>