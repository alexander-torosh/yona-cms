<div class="wrapper-in">

    <header>
        {{ partial('main/header') }}
    </header>

    {{ partial('main/menu') }}

    <div id="main">

        {{ content() }}

        {% if seo_text is defined and seo_text_inner is not defined %}
            <div class="seo-text">
                {{ seo_text }}
            </div>
        {% endif %}

    </div>

    <footer>
        {{ partial('main/footer') }}
    </footer>

</div>

{% if registry.cms['PROFILER'] %}
    {{ helper.dbProfiler() }}
{% endif %}

{{ helper.javascript('body') }}