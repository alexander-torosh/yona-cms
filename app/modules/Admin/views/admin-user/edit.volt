<p>


</p>

<form method="post" action="" class="ui form">

    <!--controls-->
    <div class="ui segment">

        <a href="/admin/admin-user" class="ui button">
            <i class="icon left arrow"></i> Back
        </a>

        <div class="ui positive submit button">
            <i class="save icon"></i> Save
        </div>

        {% if model.getId() %}
            <a href="/admin/admin-user/delete/{{ model.getId() }}" class="ui button red">
                <i class="icon trash"></i> Delete
            </a>
        {% endif %}

    </div>
    <!--end controls-->

    {{ form.renderAll() }}

</form>

<script>
    $('.ui.form').form({});
</script>