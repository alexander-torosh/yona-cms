<div class="container">

    <h1>404</h1>

    <p>Страница не найдена</p>

    {% if registry.cms['DEBUG_MODE'] %}
        <p>{{ e.getMessage() }}</p>
        <p>{{ e.getFile() }}::{{ e.getLine() }}</p>
        <pre>{{ e.getTraceAsString() }}</pre>
    {% endif %}

</div>