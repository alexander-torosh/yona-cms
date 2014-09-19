<form action="" method="post" class="ui form">

    <!--tabs-->
    <div class="ui tabular menu init">
        <a class="item {{ top }}" href="/cms/javascript/index/top" >Header</a>
        <a class="item {{ bottom }}" href="/cms/javascript/index/bottom" >Footer</a>
    </div>
    <!--/end tabs-->

    <!--tab main-->
    <div class="ui">
        {{ form.renderDecorated('text') }}
        {{ form.get('id') }}
    </div>
    <!--/end tab main-->

    <input type="hidden" name="form" value="1">
    <button type="submit" class="ui button positive"><i class="icon save"></i> Сохранить</button>
</form>