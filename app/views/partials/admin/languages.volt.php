<?php $languages = $this->helper->languages(); ?>
<?php if ($this->length($languages) > 1) { ?>
    <div class="ui menu tabular">
        <?php foreach ($languages as $lang) { ?>
            <a href="?lang=<?php echo $lang->getIso(); ?>"
               class="item<?php if ($lang->getIso() == $this->helper->constant('LANG')) { ?> active<?php } ?>">
                <?php echo $lang->getName(); ?>
            </a>
        <?php } ?>
    </div>
<?php } ?>