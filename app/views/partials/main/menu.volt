<div class="container">
    <ul id="menu">
        <li>
            <a href="{{ url(['for':'index']) }}" data-menu="home">Главная</a>
        </li>
        <li>
            <a href="{{ url(['for':'publications', 'type':'news']) }}" data-menu="publications-news">Новости</a>
        </li>
        <li>
            <a href="{{ url(['for':'contacts']) }}" data-menu="contacts">Контакты</a>
        </li>
    </ul>
</div>