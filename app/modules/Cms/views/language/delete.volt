<p>
    <a href="/cms/language/edit/{{ model.getId() }}" class="ui button">
        <i class="icon left"></i> Назад
    </a>
</p>


<form method="post" class="ui form" action="">
    <div class="ui segment">
        <b>{{ model.getName() }} - {{ model.getIso() }}</b>
    </div>
    <input type="submit" class="ui button negative" value="Удалить">
</form>
