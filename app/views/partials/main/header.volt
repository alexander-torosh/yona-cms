<a class="logo" href="{{ helper.langUrl(['for':'index']) }}">
    <img src="{{ url.path() }}static/images/logo.png" alt="">
</a>

<!--Github stars-->
<div style="position: absolute;top:10px;left:270px;z-index: 0;height: 30px;overflow: hidden;">
    <iframe frameborder="none" src="http://ghbtns.com/github-btn.html?user=oleksandr-torosh&repo=yona-cms&type=watch&count=true&size=large"></iframe>
</div>
<!--/Github stars-->

{{ helper.staticWidget('phone') }}

{% set languages = helper.languages() %}
{% if languages|length > 1 %}
    <div class="languages">
        {% for language in languages %}
            <div class="lang">
                {{ helper.langSwitcher(language['iso'], language['name']) }}
            </div>
        {% endfor %}
    </div>
{% endif %}
