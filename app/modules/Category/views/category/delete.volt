<p><a href="/category/category/edit/{{ model.getId() }}" class="ui button"><i class="icon left"></i> {{ helper.translate('Back') }}</a></p>

<div class="well">
    <p>{{ helper.translate('Are you sure want delete <b>%login%</b>?', ['login': model.getTitle()]) }}</b></p>
    <form action="" method="post">
        <input type="hidden" name="delete" value="1">
        <input type="submit" class="ui button red" value="{{ helper.translate('Confirm delete') }}">
    </form>
</div>