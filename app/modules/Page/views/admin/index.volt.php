<!--controls-->
<div class="ui segment">

    <a href="<?php echo $this->url->get(); ?>page/admin/add" class="ui button positive">
        <i class="icon plus"></i> Add New
    </a>

</div>
<!--/end controls-->

<table class="ui table very compact celled">
    <thead>
    <tr>
        <th style="width: 100px"></th>
        <th>Title</th>
        <th>Url</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($entries as $item) { ?>
        <?php $link = $this->url->get() . 'page/admin/edit/' . $item->getId(); ?>
        <tr>
            <td><a href="<?php echo $link; ?>?lang=<?php echo constant('LANG'); ?>" class="mini ui icon button"><i class="icon edit"></i>
                    id = <?php echo $item->getId(); ?></a></td>
            <td><a href="<?php echo $link; ?>?lang=<?php echo constant('LANG'); ?>"><?php echo $item->getTitle(); ?></a></td>
            <?php $url = $this->helper->langUrl(array('for' => 'page', 'slug' => $item->getSlug())); ?>
            <td><a href="<?php echo $url; ?>" target="_blank"><?php echo $url; ?></a></td>
        </tr>
    <?php } ?>
    </tbody>
</table>