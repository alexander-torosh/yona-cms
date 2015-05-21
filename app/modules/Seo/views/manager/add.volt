<form method="post" class="ui form" action="">

    <!--controls-->
    <div class="ui segment">

        <a href="/seo/manager" class="ui button">
            <i class="icon left arrow"></i> Back
        </a>

        <div class="ui positive submit button">
            <i class="save icon"></i> Save
        </div>

    </div>
    <!--end controls-->

    <div class="ui segment">
        {{ form.renderDecorated('custom_name') }}
        {{ form.renderDecorated('type') }}
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