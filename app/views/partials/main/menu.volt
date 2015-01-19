<ul id="menu">
    <li>
        <a href="{{ helper.langUrl(['for':'index']) }}">{{ helper.translate('Home') }}</a>
    </li>
    <li>
        <a href="{{ helper.langUrl(['for':'publications', 'type':'news']) }}">{{ helper.translate('News') }}</a>
    </li>
    <li>
        <a href="{{ helper.langUrl(['for':'publications', 'type':'articles']) }}">{{ helper.translate('Articles') }}</a>
    </li>
    <li>
        <a href="{{ helper.langUrl(['for':'contacts']) }}">{{ helper.translate('Contacts') }}</a>
    </li>
    <li>
        <a href="{{ url(['for':'admin']) }}" class="noajax">{{ helper.translate('Admin') }}</a>
    </li>
</ul>