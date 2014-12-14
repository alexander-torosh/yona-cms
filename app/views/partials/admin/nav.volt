<div class="ui black inverted tiered menu">
    <a class="item{{ helper.activeMenu().activeClass('admin-home') }}" href="{{ url(['for': 'admin']) }}">
        <i class="browser icon"></i> Админ-панель
    </a>

    <div class="ui dropdown item">
        <i class="font icon"></i> Контент <i class="icon dropdown"></i>

        <div class="menu">
            <a class="item{{ helper.activeMenu().activeClass('admin-page') }}" href="/page/admin">
                <i class="file outline icon"></i> Статические страницы
            </a>
            <a class="item{{ helper.activeMenu().activeClass('admin-publication') }}" href="/publication/admin">
                <i class="calendar icon"></i> Публикации
            </a>
            <a class="item{{ helper.activeMenu().activeClass('admin-slider') }}" href="/slider/admin">
                <i class="resize horizontal icon"></i> Слайдеры
            </a>
            <a class="item{{ helper.activeMenu().activeClass('admin-widget') }}" href="/widget/admin">
                <i class="text file icon"></i> Статические виджеты
            </a>
            <a class="item{{ helper.activeMenu().activeClass('admin-fm') }}" href="/file-manager">
                <i class="file image outline icon"></i> Файловый менеджер
            </a>
        </div>
    </div>
    <div class="ui dropdown item">
        <i class="lab icon"></i> SEO <i class="icon dropdown"></i>

        <div class="menu">
            <a class="item{{ helper.activeMenu().activeClass('seo-robots') }}" href="/seo/robots">
                <i class="android icon"></i> Robots.txt
            </a>
            <a class="item{{ helper.activeMenu().activeClass('seo-manager') }}" href="/seo/manager">
                <i class="lightbulb icon"></i> SEO Менеджер
            </a>
        </div>
    </div>
    <div class="ui dropdown item">
        <i class="wrench icon"></i> Администрирование <i class="icon dropdown"></i>

        <div class="menu">
            <a class="item{{ helper.activeMenu().activeClass('admin-user') }}" href="/admin/admin-user">
                <i class="user icon"></i> Администраторы
            </a>
            <a class="item{{ helper.activeMenu().activeClass('admin-cms') }}" href="/cms/configuration">
                <i class="settings icon"></i> Конфигурация
            </a>
            <a class="item{{ helper.activeMenu().activeClass('admin-language') }}" href="/cms/language">
                <i class="globe icon"></i> Языки
            </a>
            <a class="item{{ helper.activeMenu().activeClass('admin-translate') }}" href="/cms/translate">
                <i class="book icon"></i> Переводы
            </a>
            <a class="item{{ helper.activeMenu().activeClass('admin-javascript') }}" href="/cms/javascript">
                <i class="code icon"></i> {{ '<head>, <body> javascript'|escape }}
            </a>
        </div>
    </div>
    <a href="javascript:void(0);" class="item right" onclick="document.getElementById('logout-form').submit()">
        <i class="plane icon"></i> Вылет
    </a>
    <form action="/admin/index/logout" method="post" style="display: none;" id="logout-form">
        <input type="hidden" name="{{ security.getTokenKey() }}"
               value="{{ security.getToken() }}"/>
    </form>
    <a href="/" class="item right" target="_blank">
        <i class="home icon"></i> На сайт
    </a>
</div>