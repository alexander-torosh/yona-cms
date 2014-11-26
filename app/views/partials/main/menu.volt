<ul id="menu">
    <li>
        <a href="{{ helper.langUrl(['for':'index']) }}">{{ helper.translate('Главная') }}</a>
    </li>
    <li>
        <a href="{{ helper.langUrl(['for':'publications', 'type':'news']) }}">{{ helper.translate('Новости') }}</a>
    </li>
    <li>
        <a href="{{ helper.langUrl(['for':'publications', 'type':'articles']) }}">{{ helper.translate('Статьи') }}</a>
    </li>
    <li>
        <a href="{{ helper.langUrl(['for':'contacts']) }}">{{ helper.translate('Контакты') }}</a>
    </li>
    <li>
        <a href="{{ url(['for':'admin']) }}" class="noajax">{{ helper.translate('Админка') }}</a>
    </li>
</ul>