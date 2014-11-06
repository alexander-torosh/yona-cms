<link href="/static/css/gall-phalcon.css" rel="stylesheet" type="text/css" />
<script src="/static/js/gall-phalcon.js"></script>

{{ helper.title().append(title) }}

<p><a href="/slider/admin/edit/{{ model.getId() }}" class="ui blue button"><i class="left icon"></i> Назад</a></p>
<div class="well">
    <p>Вы уверенны, что хотите удалить слайдер <b>{{ model.getTitle() }}</b></p>

    <form action="" method="post">
        <input type="submit" class="ui red button" value="Подтвердить удаление">
    </form>
</div>