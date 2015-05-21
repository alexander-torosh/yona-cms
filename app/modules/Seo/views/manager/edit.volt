<form method="post" class="ui form" action="">

    <!--controls-->
    <div class="ui segment">

        <a href="/seo/manager" class="ui button">
            <i class="icon left arrow"></i> Back
        </a>

        <div class="ui positive submit button">
            <i class="save icon"></i> Save
        </div>

        {% if model.getId() %}

            <a href="/seo/manager/add" class="ui button">
                <i class="icon add"></i> Add New
            </a>

            <a href="/seo/manager/delete/{{ model.getId() }}" class="ui button red">
                <i class="icon trash"></i> Delete
            </a>

        {% endif %}

    </div>
    <!--end controls-->

    <div class="ui segment">
        {{ form.renderDecorated('custom_name') }}

        <p>Type: <b>{{ model.getTypeTitle() }}</b></p>

        {% if model.getType() == 'url' %}
            {{ form.renderDecorated('url') }}
        {% endif %}

        {% if model.getType() == 'route' %}
            {{ form.renderDecorated('route') }}
            {{ form.renderDecorated('route_params_json') }}
        {% endif %}

        {% if model.getType() == 'mca' %}
            {{ form.renderDecorated('module') }}
            {{ form.renderDecorated('controller') }}
            {{ form.renderDecorated('action') }}
        {% endif %}

        {% if model.getType() in ['route', 'mca'] %}
            {{ form.renderDecorated('language') }}
            {{ form.renderDecorated('query_params_json') }}
        {% endif %}

        <hr>

        {{ form.renderDecorated('head_title') }}
        {{ form.renderDecorated('meta_description') }}
        {{ form.renderDecorated('meta_keywords') }}
        {{ form.renderDecorated('seo_text') }}
    </div>

</form>

<!--ui semantic-->
<script>
    $('.ui.form').form({
        title: {
            identifier: 'title',
            rules: [
                {type: 'empty'}
            ]
        }
    });
</script><!--/end ui semantic-->