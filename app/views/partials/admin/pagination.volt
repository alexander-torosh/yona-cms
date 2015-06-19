{% set link = url.get() ~ substr(router.getRewriteUri(), 1) %}
<div class="ui pagination menu">
    <a class="icon item" href="{{ link }}?page={{ paginate.before }}">
        <i class="left arrow icon"></i>
    </a>
    {% if paginate.total_pages > 10 %}
        {% if paginate.current > 5 %}
            {% for i in paginate.current-4..paginate.current+5 %}
                {% if i <= paginate.total_pages %}
                    <a class="item{% if paginate.current == i %} active{% endif %}"
                       href="{{ link }}?page={{ i }}">{{ i }}</a>
                {% endif %}
            {% endfor %}
        {% else %}
            {% for i in 1..10 %}
                <a class="item{% if paginate.current == i %} active{% endif %}"
                   href="{{ link }}?page={{ i }}">{{ i }}</a>
            {% endfor %}
        {% endif %}
    {% else %}
        {% for i in 1..paginate.total_pages %}
            <a class="item{% if paginate.current == i %} active{% endif %}" href="{{ link }}?page={{ i }}">{{ i }}</a>
        {% endfor %}
    {% endif %}
    <a class="icon item" href="{{ link }}?page={{ paginate.next }}">
        <i class="right arrow icon"></i>
    </a>
</div>