<form action="/tree/admin/edit/{{ model.getId() }}" method="post" class="ui form">

    <!--controls-->
    <div class="ui segment">

        <a href="/tree/admin?lang={{ constant('LANG') }}" class="ui button">
            <i class="icon left arrow"></i> Back
        </a>

        <div class="ui positive submit button">
            <i class="save icon"></i> Save
        </div>

    </div>
    <!--end controls-->

    <div class="two fields">
        <div class="field">
            {{ form.renderDecorated('slug') }}
        </div>
        <div class="field">
            {{ form.renderDecorated('title') }}
        </div>
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
        },
        slug: {
            identifier: 'slug',
            rules: [
                {type: 'empty'}
            ]
        }
    });
</script><!--/end ui semantic-->