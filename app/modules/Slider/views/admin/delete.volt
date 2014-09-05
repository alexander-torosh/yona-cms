<link href="/vendor/semantic/css/semantic.min.css" rel="stylesheet" type="text/css" />
<script src="/vendor/semantic/javascript/semantic.min.js"></script>
<link href="/static/css/gall-phalcon.css" rel="stylesheet" type="text/css" />
<script src="/static/js/gall-phalcon.js"></script>

{{ helper.title().append(title) }}

<p><a href="/admin/gallery/edit/{{ model.getId() }}" class="ui blue button"><i class="left icon"></i> {{ helper.translate('Назад') }}</a></p>
<div class="well">
    <p>{{ helper.translate('Вы уверенны, что хотите удалить пользователя <span>%title%</span>?', ['title': model.getTitle()]) }}</b></p>

    <form action="" method="post">
        <input type="submit" class="ui red button" value="{{ helper.translate('Подтвердить удаление') }}">
    </form>
</div>