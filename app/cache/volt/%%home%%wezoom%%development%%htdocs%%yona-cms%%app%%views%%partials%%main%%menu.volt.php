<div class="container">
    <ul id="menu">
        <li>
            <a href="<?php echo $this->url->get(array('for' => 'index')); ?>" data-menu="home">Главная</a>
        </li>
        <li>
            <a href="<?php echo $this->url->get(array('for' => 'publications', 'type' => 'news')); ?>" data-menu="publications-news">Новости</a>
        </li>
        <li>
            <a href="<?php echo $this->url->get(array('for' => 'contacts')); ?>" data-menu="contacts">Контакты</a>
        </li>
    </ul>
</div>