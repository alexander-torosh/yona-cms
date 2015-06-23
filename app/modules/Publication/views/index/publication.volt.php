<article id="content" class="publication clearfix">

    <?php if ($this->helper->isAdminSession()) { ?>
        <p style="font-weight: bold;font-size:120%;">
            <a class="noajax"
               href="<?php echo $this->url->get(); ?>publication/admin/edit/<?php echo $publication->getId(); ?>?lang=<?php echo constant('LANG'); ?>">Редактировать публикацию</a>
        </p>
    <?php } ?>

    <h1><?php echo $publication->getTitle(); ?></h1>

    <section class="date"><?php echo $publication->getDate('d.m.Y'); ?></section>

    <?php if ($publication->preview_inner) { ?>
        <?php $image = $this->helper->image(array('id' => $publication->getId(), 'type' => 'publication', 'width' => 300, 'strategy' => 'w')); ?>
        <div class="image inner">
            <?php echo $image->imageHTML(); ?>
        </div>
    <?php } ?>

    <?php echo $publication->getText(); ?>

    <a href="<?php echo $this->helper->langUrl(array('for' => 'publications', 'type' => $publication->getTypeSlug())); ?>" class="back">&larr; <?php echo $this->helper->translate('Back к перечню публикаций'); ?></a>

</article>