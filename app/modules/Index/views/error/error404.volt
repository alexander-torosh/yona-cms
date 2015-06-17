<div class="container">

    <h1>404</h1>

    <p>Page not found</p>

    {% if registry.cms['DEBUG_MODE'] %}
        <p>--------------------------<br>Debug mode error details:</p>
        {% if e is defined %}
            <p>{{ e.getMessage() }}</p>
            <p>{{ e.getFile() }}::{{ e.getLine() }}</p>
            <pre>{{ e.getTraceAsString() }}</pre>
        {% endif %}
        {% if message %}
            {{ message }}
        {% endif %}
    {% endif %}

</div>