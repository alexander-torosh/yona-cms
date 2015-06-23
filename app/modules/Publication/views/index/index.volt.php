<h1><?php echo $title; ?></h1>

<div class="publications <?php echo $format; ?>">

    <?php if ($paginate->total_items > 0) { ?>
        <?php foreach ($paginate->items as $item) { ?>
            <?php echo $this->helper->modulePartial('index/format/' . $format, array('item' => $item)); ?>
        <?php } ?>
    <?php } else { ?>
        <p>Публикации отсутствуют</p>
    <?php } ?>

</div>

<?php if ($paginate->total_pages > 1) { ?>
    <div class="pagination">
        <?php echo $this->partial('main/pagination', array('paginate' => $paginate)); ?>
    </div>
<?php } ?>