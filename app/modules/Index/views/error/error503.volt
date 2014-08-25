<div class="container">

    <h1>503</h1>

    <p>Ошибка сервера</p>

    {% if registry.cms['DEBUG_MODE'] %}
        <p>{{ e.getMessage() }}</p>
        <p>{{ e.getFile() }}::{{ e.getLine() }}</p>
        <pre>{{ e.getTraceAsString() }}</pre>
    {% endif %}

</div>