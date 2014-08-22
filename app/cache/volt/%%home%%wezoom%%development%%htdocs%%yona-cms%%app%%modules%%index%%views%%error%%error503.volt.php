<div class="container">

    <h1>503</h1>

    <p>Ошибка сервера</p>

    <?php if ($this->registry->cms['DEBUG_MODE']) { ?>
        <p><?php echo $e->getMessage(); ?></p>
        <p><?php echo $e->getFile(); ?>::<?php echo $e->getLine(); ?></p>
        <pre><?php echo $e->getTraceAsString(); ?></pre>
    <?php } ?>

</div>