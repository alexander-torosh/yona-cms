<div class="ui left fixed vertical pointing inverted menu">
    <a class="item{{ helper.activeMenu().activeClass('admin-home') }} header" href="{{ url(['for': 'admin']) }}">
        Yona CMS
    </a>

    <div class="item">
        <div class="header">{{ helper.at('Contents') }} <i class="font icon"></i></div>

        <div class="menu">
            <a class="item{{ helper.activeMenu().activeClass('admin-page') }}" href="{{ url.get() }}page/admin">
                {{ helper.at('Pages') }} <i class="file outline icon"></i>
            </a>
            <a class="item{{ helper.activeMenu().activeClass('admin-publication') }}"
               href="{{ url.get() }}publication/admin">
                {{ helper.at('Publications') }} <i class="calendar icon"></i>
            </a>
            <a class="item{{ helper.activeMenu().activeClass('admin-widget') }}" href="{{ url.get() }}widget/admin">
                {{ helper.at('Widgets') }} <i class="text file icon"></i>
            </a>
            <a class="item{{ helper.activeMenu().activeClass('tree') }}" href="{{ url.get() }}tree/admin">
                {{ helper.at('Tree Categories') }} <i class="tree icon"></i>
            </a>
            <a class="item{{ helper.activeMenu().activeClass('admin-fm') }}" href="{{ url.get() }}file-manager">
                {{ helper.at('File Manager') }} <i class="file image outline icon"></i>
            </a>
        </div>
    </div>

    <div class="item">
        <div class="header">SEO <i class="lab icon"></i></div>

        <div class="menu">
            <a class="item{{ helper.activeMenu().activeClass('seo-robots') }}" href="{{ url.get() }}seo/robots">
                Robots.txt <i class="android icon"></i>
            </a>
            <a class="item{{ helper.activeMenu().activeClass('seo-sitemap') }}" href="{{ url.get() }}seo/sitemap">
                Sitemap.xml <i class="sitemap icon"></i>
            </a>
            <a class="item{{ helper.activeMenu().activeClass('seo-manager') }}" href="{{ url.get() }}seo/manager">
                SEO Manager <i class="lightbulb icon"></i>
            </a>
        </div>
    </div>
    <div class="item">
        <div class="header">{{ helper.at('Admin') }} <i class="wrench icon"></i></div>

        <div class="menu">
            <a class="item{{ helper.activeMenu().activeClass('admin-user') }}" href="{{ url.get() }}admin/admin-user">
                {{ helper.at('Manage Users') }} <i class="user icon"></i>
            </a>
            <a class="item{{ helper.activeMenu().activeClass('admin-language') }}" href="{{ url.get() }}cms/language">
                {{ helper.at('Languages') }} <i class="globe icon"></i>
            </a>
            <a class="item{{ helper.activeMenu().activeClass('admin-translate') }}" href="{{ url.get() }}cms/translate">
                {{ helper.at('Translate') }} <i class="book icon"></i>
            </a>
            <a class="item{{ helper.activeMenu().activeClass('admin-javascript') }}"
               href="{{ url.get() }}cms/javascript">
                {{ '<head>, <body> javascript'|escape }} <i class="code icon"></i>
            </a>
        </div>
    </div>
    <div class="item">
        <a href="{{ url.get() }}" class="ui primary tiny button" target="_blank">
            <i class="home icon"></i>{{ helper.at('View Site') }}
        </a>
        <br><br>
        <a href="javascript:void(0);" class="ui tiny button" onclick="document.getElementById('logout-form').submit()">
            <i class="plane icon"></i>{{ helper.at('Logout') }}
        </a>

        <form action="{{ url.get() }}admin/index/logout" method="post" style="display: none;" id="logout-form">
            <input type="hidden" name="{{ security.getTokenKey() }}"
                   value="{{ security.getToken() }}">
        </form>
    </div>
</div>