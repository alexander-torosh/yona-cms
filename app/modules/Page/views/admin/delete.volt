<p>
    <a href="/page/admin/edit/{{ model.getId() }}?lang={{ constant('LANG') }}" class="ui button">
        <i class="icon left"></i> Назад
    </a>
</p>


<form method="post" class="ui form" action="">
    <div class="ui segment">
        <b>{{ model.getTitle() }}</b>
    </div>
    <input type="submit" class="ui button negative" value="Удалить">
</form>
