<ul class="numbers">
    <li>
        <a href="{{ url }}?page={{ paginate.before }}" class="prev">&larr;</a>
    </li>
    {% for i in 1..paginate.total_pages %}
        <li>
            <a href="{{ url }}?page={{ i }}" class="{% if paginate.current == i %} active{% endif %}">{{ i }}</a>
        </li>
    {% endfor %}
    <li>
        <a href="{{ url }}?page={{ paginate.next }}" class="next">&rarr;</a>
    </li>
</ul>