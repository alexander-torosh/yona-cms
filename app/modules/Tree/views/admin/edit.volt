<form action="{{ url.get() }}tree/admin/edit/{{ model.getId() }}?lang={{ constant('LANG') }}" method="post" class="ui form">

    <!--controls-->
    <div class="ui segment">

        <a href="{{ url.get() }}tree/admin?lang={{ constant('LANG') }}" class="ui button">
            <i class="icon left arrow"></i> {{ helper.at('Back') }}
        </a>

        <div class="ui positive submit button">
            <i class="save icon"></i> {{ helper.at('Save') }}
        </div>

    </div>
    <!--end controls-->

    <div class="ui segment">
        <div class="two fields">
            <div class="field">
                {{ form.renderDecorated('slug') }}
            </div>
            <div class="field">
                {{ form.renderDecorated('title') }}
            </div>
        </div>
    </div>

</form>

<!--ui semantic-->
<script>
    $('.ui.form').form({
        fields: {
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
        }
    });
</script><!--/end ui semantic-->