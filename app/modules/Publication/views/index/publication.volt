<article id="content" class="publication clearfix">

    {% if helper.isAdminSession() %}
        <p style="font-weight: bold;font-size:120%;">
            <a class="noajax"
               href="{{ url.get() }}publication/admin/edit/{{ publicationResult.p.getId() }}?lang={{ constant('LANG') }}">{{ helper.at('Edit publication') }}</a>
        </p>
    {% endif %}

    <h1>{{ publicationResult.title }}</h1>

    {% if publicationResult.p.getTypeDisplayDate() %}
    <section class="date">{{ publicationResult.p.getDate('d.m.Y') }}</section>
    {% endif %}

    {% if publicationResult.p.preview_inner %}
        {% set image = helper.image([
        'id': publicationResult.p.getId(),
        'type': 'publication',
        'width': 300,
        'strategy': 'w'
        ]) %}
        <div class="image inner">
            {{ image.imageHTML() }}
        </div>
    {% endif %}

    {{ publicationResult.text }}

    <a href="{{ helper.langUrl(['for':'publications','type':publicationResult.t_slug]) }}" class="back">&larr; {{ helper.translate('Back to publications list') }}</a>

</article>