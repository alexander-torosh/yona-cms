<div class="ui stackable three column grid container">

    <div class="column">
        <a class="logo" href="{{ helper.langUrl(['for':'index']) }}">
            <img src="{{ url.path() }}static/images/logo.png" alt="">
        </a>
    </div>
    <div class="column">

        <!--Github stars-->
        <div style="z-index: 0;height: 30px;overflow: hidden; margin-bottom: 10px;">
            <iframe frameborder="none"
                    src="http://ghbtns.com/github-btn.html?user=oleksandr-torosh&repo=yona-cms&type=watch&count=true&size=large"></iframe>
        </div>
        <!--/Github stars-->

        {{ helper.staticWidget('phone') }}
    </div>
    <div class="column">
        {% set languages = helper.languages() %}
        {% if languages|length > 1 %}
            <ul class="languages">
                {% for language in languages %}
                    <li class="lang">
                        {{ helper.langSwitcher(language['iso'], language['name']) }}
                    </li>
                {% endfor %}
            </ul>
        {% endif %}
    </div>

</div>