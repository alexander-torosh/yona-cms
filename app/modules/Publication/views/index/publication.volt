<article id="content" class="publication clearfix">

    {% if helper.isAdminSession() %}
        <p style="font-weight: bold;font-size:120%;">
            <a class="noajax"
               href="/publication/admin/edit/{{ publication.getId() }}">{{ helper.translate('Редактировать публикацию') }}</a>
        </p>
    {% endif %}

    <h1>{{ publication.getTitle() }}</h1>

    {% if publication.getType() in ['events'] %}
        <section class="date">{{ publication.getDate('d.m.Y') }}</section>
    {% endif %}

    {% if publication.preview_inner %}
        {% set image = helper.image([
        'id': publication.getId(),
        'type': 'publication',
        'width': 205,
        'strategy': 'w'
        ]) %}
        <div class="image inner">
            {{ image.imageHTML() }}
        </div>
    {% endif %}

    {{ publication.getText() }}

    <p>
        <a href="{{ url(['for':'publications','type':publication.getType()]) }}">&larr; Назад к перечню публикаций</a>
    </p>

</article>