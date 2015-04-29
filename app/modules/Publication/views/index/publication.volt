<article id="content" class="publication clearfix">

    {% if helper.isAdminSession() %}
        <p style="font-weight: bold;font-size:120%;">
            <a class="noajax"
               href="/publication/admin/edit/{{ publication.getId() }}?lang={{ constant('LANG') }}">Редактировать публикацию</a>
        </p>
    {% endif %}

    <h1>{{ publication.getTitle() }}</h1>

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

    <a href="{{ helper.langUrl(['for':'publications','type':publication.getTypeSlug()]) }}" class="back">&larr; {{ helper.translate('Back к перечню публикаций') }}</a>

</article>