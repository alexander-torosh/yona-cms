<link href="/static/css/gall-phalcon.css" rel="stylesheet" type="text/css" />
<script src="/static/js/gall-phalcon.js"></script>

{{ helper.title().append(title) }}

<p><a href="/slider/admin/edit/{{ model.getId() }}" class="ui blue button"><i class="left icon"></i> {{ helper.translate('Назад') }}</a></p>
<div class="well">
    <p>{{ helper.translate('Вы уверенны, что хотите удалить слайдер "<span>%title%</span>"?', ['title': model.getTitle()]) }}</b></p>

    <form action="" method="post">
        <input type="submit" class="ui red button" value="{{ helper.translate('Подтвердить удаление') }}">
    </form>
</div>