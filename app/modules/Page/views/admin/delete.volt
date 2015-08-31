<div class="ui segment">
    <a href="{{ url.get() }}page/admin/edit/{{ model.getId() }}?lang={{ constant('LANG') }}" class="ui button">
        <i class="icon left arrow"></i> {{ helper.at('Back') }}
    </a>

    <form method="post" class="ui form segment negative message" action="">
        <p>{{ helper.at('Remove page') }} <b>{{ model.getTitle() }}</b>?</p>
        <button type="submit" class="ui button negative"><i class="icon trash"></i> {{ helper.at('Delete') }}</button>
    </form>

</div>