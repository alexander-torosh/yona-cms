<h1><?php echo $title; ?></h1>

<div class="publications">

    <div class="limit">
        <section>Отображать по:</section>
        <ul class="numbers">
            <?php $publicationsLink = $this->url->get(array('for' => 'publications', 'type' => $type)); ?>
            <li><a href="<?php echo $publicationsLink; ?>?limit=5" class="ajax<?php if ($limit == 5) { ?> active<?php } ?>">5</a>
            </li>
            <li><a href="<?php echo $publicationsLink; ?>?limit=10" class="ajax<?php if ($limit == 10) { ?> active<?php } ?>">10</a>
            </li>
            <li><a href="<?php echo $publicationsLink; ?>?limit=all"
                   class="infinity ajax<?php if ($limit == 'all') { ?> active<?php } ?>">
                    &nbsp;</a></li>
        </ul>
    </div>

    <?php foreach ($paginate->items as $item) { ?>
        <div class="item">
            <div class="row">
                <?php $image = $this->helper->image(array('id' => $item->getId(), 'type' => 'publication', 'width' => 205, 'strategy' => 'w')); ?>
                <?php $link = $this->url->get(array('for' => 'publication', 'type' => $item->getType(), 'slug' => $item->getSlug())); ?>
                <a class="image" href="<?php echo $link; ?>"><?php echo $image->imageHTML(); ?></a>

                <div class="text">
                    <a href="<?php echo $link; ?>" class="title"><?php echo $item->getTitle(); ?></a>
                    <section class="announce"><?php echo $this->helper->announce($item->getText(), 300); ?></section>
                </div>
            </div>
        </div>
    <?php } ?>

</div>

<?php if ($paginate->total_pages > 1) { ?>
    <div class="pagination">
        <?php echo $this->partial('main/pagination', array('paginate' => $paginate, 'url' => $publicationsLink)); ?>
    </div>
<?php } ?>