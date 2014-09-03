<!--controls-->
<div class="ui segment">

    <a href="/projects/admin/add" class="ui button positive">
        <i class="icon plus"></i> Добавить
    </a>

</div>
<!--/end controls-->

<table class="ui compact table small segment">
    <tr>
        <th></th>
        <th>Сорт.</th>
        <th>Название объекта</th>
        <th>Расположение</th>
        <th>Описание</th>
        <th>Отображается</th>
    </tr>
    <?php foreach ($entries as $item) { ?>
        <?php $link = '/projects/admin/edit/' . $item->getId(); ?>
        <tr>
            <td><a href="<?php echo $link; ?>" class="mini ui icon button"><i class="icon edit"></i> id = <?php echo $item->getId(); ?></a></td>
            <td><?php echo $item->getSortorder(); ?></td>
            <td><a href="<?php echo $link; ?>"><?php echo $item->getTitle(); ?></a></td>
            <td><?php echo $item->getLocation(); ?></td>
            <td><?php echo $item->getDescription(); ?></td>
            <td><?php if ($item->visible) { ?><i class="icon plus"></i><?php } ?></td>
        </tr>
    <?php } ?>
</table>