<div class="container">

    {{ content() }}

    {% if seo_text and not seo_text_inner %}
        <div class="seo-text">
            {{ seo_text }}
        </div>
    {% endif %}

</div>