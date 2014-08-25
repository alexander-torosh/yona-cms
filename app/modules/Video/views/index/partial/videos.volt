<div class="container videos-list bg" data-rotation="target: .wrapper > .cluster; nav: .wrapper;">
    <div class="wrapper">
        {% if videos.count() > 4 %}
            <div class="arrow-left prev"></div>
        {% endif %}
        {% set clusters_count = 0 %}
        {% set clusters_complete = 0 %}
        {% set diff = 4 - (videos.count() % 4) %}
        {% for video in videos %}
            {% if ((loop.index + 3) % 4 == 0) %}
                {% set clusters_count = clusters_count + 1 %}
                <!--cluster-->
                <div class="cluster{% if loop.index == 1 %} active{% endif %}">
            {% endif %}
            <a href="/video/{{ video.getId() }}" class="item ajax{% if video.getId() == activeVideo %} active{% endif %}">
                <img alt="{{ video.getTitle()|escape_attr }}" width="200" src="{{ video.getYoutubeImageSrc() }}">
                <section class="title">{{ video.getTitle() }}</section>
            </a>
            {% if loop.index % 4 != 0 and loop.index != videos.count() %}
                <div class="delimiter"></div>
            {% endif %}
            {% if loop.index % 4 != 0 and loop.index == videos.count() %}
                <div class="delimiter hide"></div>
            {% endif %}
            {% if (loop.index % 4 == 0) %}
                {% set clusters_complete = clusters_set + 1 %}
                </div><!--/ end cluster-->
            {% endif %}
        {% endfor %}
        {% for i in 1..(diff) %}
            <div class="item"></div>
            {% if i != diff %}
                <div class="delimiter hide"></div>
            {% endif %}
        {% endfor %}
        {% if clusters_count != clusters_complete %}
    </div>
    <!--/ end cluster-->
    {% endif %}
    {% if videos.count() > 4 %}
        <div class="arrow-right next"></div>
    {% endif %}
</div>
</div>