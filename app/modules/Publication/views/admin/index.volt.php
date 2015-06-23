<!--controls-->
<div class="ui segment">

    <a href="<?php echo $this->url->get(); ?>publication/admin/<?php echo $type; ?>/add" class="ui button positive">
        <i class="icon plus"></i> <?php echo $this->helper->at('Add New'); ?>
    </a>

    <a href="<?php echo $this->url->get(); ?>publication/type" class="ui button">
        <i class="icon list"></i> <?php echo $this->helper->at('Publications types'); ?>
    </a>

</div>
<!--/end controls-->

<div class="ui tabular menu">
    <a href="<?php echo $this->url->get(); ?>publication/admin?lang=<?php echo constant('LANG'); ?>"
       class="item<?php if (!$type_id) { ?> active<?php } ?>"><?php echo $this->helper->at('All'); ?></a>
    <?php foreach ($types as $type_el) { ?>
        <a href="<?php echo $this->url->get(array('for' => 'publications_admin', 'type' => $type_el->getSlug())); ?>?lang=<?php echo constant('LANG'); ?>"
           class="item<?php if ($type_el->getId() == $type_id) { ?> active<?php } ?>">
            <?php echo $type_el->getTitle(); ?>
        </a>
    <?php } ?>
</div>

<?php if ($paginate->total_items > 0) { ?>

    <table class="ui table very compact celled">
        <thead>
        <tr>
            <th style="width: 100px"></th>
            <th><?php echo $this->helper->at('Title'); ?></th>
            <th><?php echo $this->helper->at('Type of Publication'); ?></th>
            <th><?php echo $this->helper->at('Publication Date'); ?></th>
            <th><?php echo $this->helper->at('Thumbs Inside'); ?></th>
            <th><?php echo $this->helper->at('Url'); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($paginate->items as $item) { ?>
            <?php $link = $this->url->get() . 'publication/admin/edit/' . $item->getId(); ?>
            <tr>
                <td><a href="<?php echo $link; ?>?lang=<?php echo constant('LANG'); ?>" class="mini ui icon button"><i
                                class="icon edit"></i> id = <?php echo $item->getId(); ?></a></td>
                <td><a href="<?php echo $link; ?>?lang=<?php echo constant('LANG'); ?>"><?php echo $item->getTitle(); ?></a></td>
                <td><?php echo $item->getTypeTitle(); ?></td>
                <td><?php echo $item->getDate(); ?></td>
                <td><?php if ($item->preview_inner) { ?><i class="icon checkmark green"></i><?php } ?></td>
                <?php $url = $this->helper->langUrl(array('for' => 'publication', 'type' => $item->getTypeSlug(), 'slug' => $item->getSlug())); ?>
                <td><a href="<?php echo $url; ?>" target="_blank"><?php echo $url; ?></a></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
<?php } else { ?>
    <p><?php echo $this->helper->at('Entries not found'); ?></p>
<?php } ?>

<?php if ($paginate->total_pages > 1) { ?>
    <div class="pagination">
        <?php echo $this->partial('admin/pagination', array('paginate' => $paginate)); ?>
    </div>
<?php } ?>
