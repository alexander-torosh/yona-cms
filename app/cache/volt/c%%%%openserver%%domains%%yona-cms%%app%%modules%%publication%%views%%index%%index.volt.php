<h1><?php echo $title; ?></h1>

<div class="publications">

    <?php if ($paginate->total_items > 0) { ?>
        <?php foreach ($paginate->items as $item) { ?>
            <?php $image = $this->helper->image(array('id' => $item->getId(), 'type' => 'publication', 'width' => 240, 'strategy' => 'w')); ?>
            <?php $link = $this->url->get(array('for' => 'publication', 'type' => $item->getType(), 'slug' => $item->getSlug())); ?>
            <?php if ($image->isExists()) { ?><?php $imageExists = true; ?><?php } else { ?><?php $imageExists = false; ?><?php } ?>
            <div class="item<?php if ($imageExists) { ?> with-image<?php } ?>">
                <?php if ($imageExists) { ?>
                    <a class="image" href="<?php echo $link; ?>"><?php echo $image->imageHTML(); ?></a>
                <?php } ?>
                <div class="text">
                    <a href="<?php echo $link; ?>" class="title"><?php echo $item->getTitle(); ?></a>
                    <section class="announce"><?php echo $this->helper->announce($item->getText(), 300); ?></section>
                    <a href="<?php echo $link; ?>" class="details"><?php echo $this->helper->translate('Подробнее'); ?> &rarr;</a>
                </div>
            </div>
        <?php } ?>
    <?php } else { ?>
        <p>Публикации отсутствуют</p>
    <?php } ?>

</div>

<?php if ($paginate->total_pages > 1) { ?>
    <div class="pagination">
        <?php echo $this->partial('main/pagination', array('paginate' => $paginate, 'url' => $publicationsLink)); ?>
    </div>
<?php } ?>