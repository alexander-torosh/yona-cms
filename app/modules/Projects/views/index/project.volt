<div id="project">
    <div class="wrapper">
        <div class="sidebar">
            <div class="back clearfix">
                <a href="/projects">
                    <div class="ico"></div>
                    <section class="c">ВЕРНУТЬСЯ<br>
                        <small>назад в галерею</small>
                    </section>
                </a>
            </div>
            <div class="info">
                <div class="reynaers clearfix upper">
                    <div class="ico"></div>
                    <section class="c">Алюминиевое<br>остекление<br>Reynaers</section>
                </div>
                <div class="location clearfix">
                    <div class="ico"></div>
                    <section class="c">{{ project.getLocation() }}</section>
                </div>
                <div class="description clearfix">
                    <div class="ico"></div>
                    <section class="c">{{ project.getDescription() }}</section>
                </div>
            </div>
        </div>
        <div class="image">
            {% set image = helper.image([
            'type': 'project',
            'id': projectImage.getId(),
            'width': 1000
            ]) %}
            {{ image.imageHTML() }}
        </div>

        <div class="images">
            {% for img in project.getProjectImages() %}
                <a href="?image={{ loop.index }}" class="item ajax{% if imagePos == loop.index %} active{% endif %}">
                    {% set image = helper.image([
                    'type': 'project',
                    'id': img.getId(),
                    'width': 300,
                    'height': 170,
                    'strategy': 'a',
                    'widthHeight': false
                    ]) %}
                    {{ image.imageHTML() }}
                </a>
                {% if loop.index != 4 %}
                    <div class="delimiter"></div>
                {% endif %}
            {% endfor %}
        </div>
    </div>
</div>