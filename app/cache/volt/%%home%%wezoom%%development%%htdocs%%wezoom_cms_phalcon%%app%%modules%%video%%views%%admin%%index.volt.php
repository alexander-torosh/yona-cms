<!--controls-->
<div class="ui segment">

    <a href="/video/admin/add" class="ui button positive">
        <i class="icon plus"></i> Добавить
    </a>

</div>
<!--/end controls-->

<table class="ui compact table small segment">
    <tr>
        <th></th>
        <th>Сорт.</th>
        <th>Название</th>
    </tr>
    <?php foreach ($entries as $item) { ?>
        <?php $link = '/video/admin/edit/' . $item->getId(); ?>
        <tr>
            <td><a href="<?php echo $link; ?>" class="mini ui icon button"><i class="icon edit"></i> id = <?php echo $item->getId(); ?></a></td>
            <td><?php echo $item->getSortorder(); ?></td>
            <td><a href="<?php echo $link; ?>"><?php echo $item->getTitle(); ?></a></td>
        </tr>
    <?php } ?>
</table>