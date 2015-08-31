{% set image = helper.image([
'id': item.getId(),
'type': 'publication',
'width': 300,
'strategy': 'w'
]) %}
{% set link = helper.langUrl(['for':'publication', 'type':item.getTypeSlug(), 'slug':item.getSlug()]) %}
{% if image.isExists() %}{% set imageExists = true %}{% else %}{% set imageExists = false %}{% endif %}
<div class="item{% if imageExists %} with-image{% endif %}">
    {% if imageExists %}
        <a class="image" href="{{ link }}">{{ image.imageHTML() }}</a>
    {% endif %}
    <div class="text">
        <section class="date">{{ item.getDate('d.m.Y') }}</section>
        <a href="{{ link }}" class="title">{{ item.getTitle() }}</a>
        <section class="announce">{{ helper.announce(item.getText(), 300) }}</section>

        <a href="{{ link }}" class="details">{{ helper.translate('Подробнее') }} &rarr;</a>
    </div>
</div>