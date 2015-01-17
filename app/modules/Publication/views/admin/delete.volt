<div class="ui segment">
    <a href="/publication/admin/edit/{{ model.getId() }}?lang={{ constant('LANG') }}" class="ui button">
        <i class="icon left arrow"></i> Back
    </a>
</div>

<form method="post" class="ui negative message form" action="">
    <p>Delete publication <b>{{ model.getTitle() }}</b>?</p>
    <button type="submit" class="ui button negative"><i class="icon trash"></i> Delete</button>
</form>