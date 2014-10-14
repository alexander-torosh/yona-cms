{% set languages = helper.languages() %}
{% if languages|length > 1 %}
    <div class="ui menu tabular">
        {% for lang in languages %}
            <a href="?lang={{ lang.getIso() }}"
               class="item{% if lang.getIso() == helper.constant('LANG') %} active{% endif %}">
                {{ lang.getName() }}
            </a>
        {% endfor %}
    </div>
{% endif %}