<div class="container">

    <h1>403</h1>

    <p>Permission Denied</p>

    {% if registry.cms['DEBUG_MODE'] %}
        <p>--------------------------<br>Debug mode error details:</p>
        {% if message %}
            {{ message }}
        {% endif %}
    {% endif %}

</div>