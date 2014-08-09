<p><a href="/admin/admin-user/edit/{{ model.getId() }}" class="ui button"><i
                class="icon left"></i> {{ helper.translate('Назад') }}</a></p>


<form method="post" class="ui form" action="">
    <div class="ui segment">
        {{ helper.translate('Вы уверены что хотите удалить пользователя <b>%login%</b>?', ['login': model.getLogin()]) }}
    </div>
    <input type="submit" class="ui button negative" value="{{ helper.translate('Подтвердить удаление') }}">
</form>
