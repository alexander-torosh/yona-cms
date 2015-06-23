Working on <a href="http://yonacms.com/" target="_blank">YonaCMS</a>
<a class="device-version noajax"
   href="<?php echo $this->url->get(); ?>?mobile=<?php if (constant('MOBILE_DEVICE') == true) { ?>false<?php } else { ?>true<?php } ?>">
    <?php if (constant('MOBILE_DEVICE') == true) { ?><?php echo $this->helper->translate('Полная версия'); ?><?php } else { ?><?php echo $this->helper->translate('Мобильная версия'); ?><?php } ?>
</a>