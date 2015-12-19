<article id="content" class="publication clearfix">

    {% if helper.isAdminSession() %}
        <p style="font-weight: bold;font-size:120%;">
            <a class="noajax"
               href="{{ url.get() }}publication/admin/edit/{{ publication.getId() }}?lang={{ constant('LANG') }}">{{ helper.at('Edit publication') }}</a>
        </p>
    {% endif %}

    <h1 class="ui header">{{ publication.getTitle() }}</h1>

    <section class="date">{{ publication.getDate('d.m.Y') }}</section>

    {% if publication.preview_inner %}
        {% set image = helper.image([
        'id': publication.getId(),
        'type': 'publication',
        'width': 300,
        'strategy': 'w'
        ]) %}
        <div class="image inner">
            {{ image.imageHTML() }}
        </div>
    {% endif %}

    {{ publication.getText() }}

    <a href="{{ helper.langUrl(['for':'publications','type':publication.getTypeSlug()]) }}" class="back">&larr; {{ helper.translate('Back to publications list') }}</a>

</article>