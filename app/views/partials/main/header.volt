<a class="logo" href="{{ helper.langUrl(['for':'index']) }}">
    <img src="/static/images/logo.png" alt="">
</a>

<!--Github stars-->
<div style="position: absolute;top:10px;left:270px">
    <iframe frameborder="none" src="http://ghbtns.com/github-btn.html?user=oleksandr-torosh&repo=yona-cms&type=watch&count=true&size=large"></iframe>
</div>
<!--/Github stars-->

{% set languages = helper.languages() %}
{% if languages.count() > 1 %}
    <div class="languages">
        {% for language in languages %}
            <div class="lang">
                {{ helper.langSwitcher(language.getIso(), language.getName()) }}
            </div>
        {% endfor %}
    </div>
{% endif %}
