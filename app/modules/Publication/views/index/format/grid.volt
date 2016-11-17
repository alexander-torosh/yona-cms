{% set image = helper.image([
'id': item.p.getId(),
'type': 'publication',
'width': 300,
'height': 240,
'strategy': 'a'
]) %}
{% set link = helper.langUrl(['for':'publication', 'type':item.t_slug, 'slug':item.p.getSlug()]) %}
{% if image.isExists() %}{% set imageExists = true %}{% else %}{% set imageExists = false %}{% endif %}
<div class="item">
    {% if imageExists %}
        <a class="image" href="{{ link }}">{{ image.imageHTML() }}</a>
    {% endif %}
    <div class="text">
    {% if item.p.getTypeDisplayDate() %}
        <section class="date">{{ item.p.getDate('d.m.Y') }}</section>
    {% endif %}
        <a href="{{ link }}" class="title">{{ item.title }}</a>
        <section class="announce">{{ helper.announce(item.text, 300) }}</section>

        <a href="{{ link }}" class="details">{{ helper.translate('Подробнее') }} &rarr;</a>
    </div>
</div>