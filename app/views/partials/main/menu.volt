<div class="container">
    <ul id="menu">
        <li>
            <a href="{{ url(['for':'index']) }}" data-menu="home">{{ helper.translate('Главная') }}</a>
        </li>
        <li>
            <a href="{{ url(['for':'publications', 'type':'news']) }}" data-menu="publications-news">{{ helper.translate('Новости') }}</a>
        </li>
        <li>
            <a href="{{ url(['for':'contacts']) }}" data-menu="contacts">{{ helper.translate('Контакты') }}</a>
        </li>
    </ul>
</div>