<div class="ui segment">
    <a href="/admin/admin-user/edit/{{ model.getId() }}?lang={{ constant('LANG') }}" class="ui button">
        <i class="icon left arrow"></i> Назад
    </a>
</div>

<form method="post" class="ui negative message form" action="">
    <p>Удалить пользователя <b>{{ model.getLogin() }}</b>?</p>
    <button type="submit" class="ui button negative"><i class="icon trash"></i> Удалить</button>
</form>