<?php if ($entries) { ?>
    <div class="widget last publications">
        <h3><?php echo $this->helper->translate('Latest news'); ?></h3>

        <div class="items">
            <?php foreach ($entries as $item) { ?>
                <?php $url = $this->helper->langUrl(array('for' => 'publication', 'type' => $item->getTypeSlug(), 'slug' => $item->getSlug())); ?>
                <?php $image = $this->helper->image(array('id' => $item->getId(), 'type' => 'publication', 'width' => 100, 'strategy' => 'w'), array('alt' => $this->escaper->escapeHtmlAttr($item->getTitle()))); ?>
                <div class="item">
                    <?php if ($image->isExists()) { ?>
                        <a href="<?php echo $url; ?>" title="<?php echo $this->escaper->escapeHtmlAttr($item->getTitle()); ?>" class="image" rel="nofollow">
                            <?php echo $image->imageHTML(); ?>
                        </a>
                    <?php } ?>
                    <div class="content">
                        <a href="<?php echo $url; ?>" title="<?php echo $this->escaper->escapeHtmlAttr($item->getTitle()); ?>" class="title">
                            <?php echo $item->getTitle(); ?>
                        </a>

                        <div class="date"><?php echo $item->getDate('d.m.Y'); ?></div>

                        <div class="announce">
                            <?php echo $this->helper->announce($item->getText(), 130); ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
<?php } ?>