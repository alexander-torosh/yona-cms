<?php $image = $this->helper->image(array('id' => $item->getId(), 'type' => 'publication', 'width' => 300, 'strategy' => 'w')); ?>
<?php $link = $this->helper->langUrl(array('for' => 'publication', 'type' => $item->getTypeSlug(), 'slug' => $item->getSlug())); ?>
<?php if ($image->isExists()) { ?><?php $imageExists = true; ?><?php } else { ?><?php $imageExists = false; ?><?php } ?>
<div class="item<?php if ($imageExists) { ?> with-image<?php } ?>">
    <?php if ($imageExists) { ?>
        <a class="image" href="<?php echo $link; ?>"><?php echo $image->imageHTML(); ?></a>
    <?php } ?>
    <div class="text">
        <section class="date"><?php echo $item->getDate('d.m.Y'); ?></section>
        <a href="<?php echo $link; ?>" class="title"><?php echo $item->getTitle(); ?></a>
        <section class="announce"><?php echo $this->helper->announce($item->getText(), 300); ?></section>

        <a href="<?php echo $link; ?>" class="details"><?php echo $this->helper->translate('Подробнее'); ?> &rarr;</a>
    </div>
</div>