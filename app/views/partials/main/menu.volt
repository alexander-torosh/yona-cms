<ul id="menu">
    <li>
        <a href="{{ helper.langUrl(['for':'index']) }}" data-menu="home">{{ helper.translate('Главная') }}</a>
    </li>
    <li>
        <a href="{{ helper.langUrl(['for':'publications', 'type':'news']) }}"
           data-menu="publications-news">{{ helper.translate('Новости') }}</a>
    </li>
    <li>
        <a href="{{ helper.langUrl(['for':'publications', 'type':'articles']) }}"
           data-menu="publications-news">{{ helper.translate('Статьи') }}</a>
    </li>
    <li>
        <a href="{{ helper.langUrl(['for':'contacts']) }}" data-menu="contacts">{{ helper.translate('Контакты') }}</a>
    </li>
</ul>