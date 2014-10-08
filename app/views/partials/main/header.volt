<a class="logo" href="{{ helper.langUrl(['for':'index']) }}">
    <img src="/static/images/logo.png" alt="">
</a>

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
