<div class="container">

    <h1>503</h1>

    <p>Ошибка сервера</p>

    {% if registry.cms['DEBUG_MODE'] %}
        {{ e.getMessage() ~ "\n" ~
        " File=", e.getFile(), "\n"~
        " Line=", e.getLine(), "\n"
        <pre>{{ e.getTraceAsString() }}</pre>
    {% endif %}

</div>