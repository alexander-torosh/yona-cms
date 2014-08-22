<article id="content" class="publication clearfix">
    <h1><?php echo $publication->getTitle(); ?></h1>
    <?php if ($this->isIncluded($publication->getType(), array('events'))) { ?>
        <section class="date"><?php echo $publication->getDate('d.m.Y'); ?></section>
    <?php } ?>
    <?php if ($publication->preview_inner) { ?>
        <?php $image = $this->helper->image(array('id' => $publication->getId(), 'type' => 'publication', 'width' => 205, 'strategy' => 'w')); ?>
        <div class="image inner">
            <?php echo $image->imageHTML(); ?>
        </div>
    <?php } ?>
    <?php echo $publication->getText(); ?>
    <p><a href="<?php echo $this->url->get(array('for' => 'publications', 'type' => $publication->getType())); ?>">&larr; Назад к перечню публикаций</a>
    </p>
</article>