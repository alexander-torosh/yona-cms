<div class="central">
    <article>
        <?php echo $page->getText(); ?>
    </article>
</div>
<div class="sidebar">
    <?php echo $this->helper->widget('Publication')->lastNews(); ?>
</div>

