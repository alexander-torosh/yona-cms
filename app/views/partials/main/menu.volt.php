<ul id="menu">
    <?php echo $this->helper->menu->item($this->helper->translate('Home'), 'index', $this->helper->langUrl(array('for' => 'index'))); ?>
    <?php echo $this->helper->menu->item($this->helper->translate('News'), 'news', $this->helper->langUrl(array('for' => 'publications', 'type' => 'news'))); ?>
    <?php echo $this->helper->menu->item($this->helper->translate('Articles'), 'articles', $this->helper->langUrl(array('for' => 'publications', 'type' => 'articles'))); ?>
    <?php echo $this->helper->menu->item($this->helper->translate('Contacts'), 'contacts', $this->helper->langUrl(array('for' => 'contacts'))); ?>
    <?php echo $this->helper->menu->item($this->helper->translate('Admin'), null, $this->url->get(array('for' => 'admin')), array('li' => array('class' => 'last'), 'a' => array('class' => 'noajax'))); ?>
    
</ul>