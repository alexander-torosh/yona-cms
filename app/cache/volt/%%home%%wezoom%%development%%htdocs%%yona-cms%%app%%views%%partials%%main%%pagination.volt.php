<ul class="numbers">
    <li>
        <a href="<?php echo $url; ?>?page=<?php echo $paginate->before; ?>" class="prev">&larr;</a>
    </li>
    <?php foreach (range(1, $paginate->total_pages) as $i) { ?>
        <li>
            <a href="<?php echo $url; ?>?page=<?php echo $i; ?>" class="<?php if ($paginate->current == $i) { ?> active<?php } ?>"><?php echo $i; ?></a>
        </li>
    <?php } ?>
    <li>
        <a href="<?php echo $url; ?>?page=<?php echo $paginate->next; ?>" class="next">&rarr;</a>
    </li>
</ul>