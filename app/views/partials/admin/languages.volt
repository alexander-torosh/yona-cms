{% set languages = helper.languages() %}
{% if languages|length > 1 %}
    <div class="ui menu tabular">
        {% for lang in languages %}
            <a href="?lang={{ lang['iso'] }}"
               class="item{% if lang['iso'] == helper.constant('LANG') %} active{% endif %}">
                {{ lang['name'] }}
            </a>
        {% endfor %}
    </div>
{% endif %}