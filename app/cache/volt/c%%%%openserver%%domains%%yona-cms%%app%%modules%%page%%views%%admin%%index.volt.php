<!--controls-->
<div class="ui segment">

    <a href="/page/admin/add" class="ui button positive">
        <i class="icon plus"></i> Добавить
    </a>

</div>
<!--/end controls-->

<table class="ui compact table small segment">
    <tr>
        <th style="width: 100px"></th>
        <th>Название</th>
        <th>Ссылка</th>
    </tr>
    <?php foreach ($entries as $item) { ?>
        <?php $link = '/page/admin/edit/' . $item->getId(); ?>
        <tr>
            <td><a href="<?php echo $link; ?>" class="mini ui icon button"><i class="icon edit"></i> id = <?php echo $item->getId(); ?></a></td>
            <td><a href="<?php echo $link; ?>"><?php echo $item->getTitle(); ?></a></td>
            <?php $url = $this->url->get(array('for' => 'page', 'slug' => $item->getSlug())); ?>
            <td><a href="<?php echo $url; ?>" target="_blank"><?php echo $url; ?></a></td>
        </tr>
    <?php } ?>
</table>