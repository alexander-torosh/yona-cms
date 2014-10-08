<div class="wrapper-in">

    <header>
        {{ partial('main/header') }}
    </header>

    {{ partial('main/menu') }}

    <div id="main">

        {{ content() }}

        {% if seo_text and not seo_text_inner %}
            <div class="seo-text">
                {{ seo_text }}
            </div>
        {% endif %}

    </div>

    <footer>
        {{ partial('main/footer') }}
    </footer>

</div>

{# partial('main/callback') #}

{% if config.profiler %}
    {{ helper.dbProfiler() }}
{% endif %}

{{ helper.javascript('body') }}
