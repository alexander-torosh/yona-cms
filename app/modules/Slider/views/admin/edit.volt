<link href="/static/css/admin/slider.css" rel="stylesheet" type="text/css" />
<script src="/static/js/admin/slider.js"></script>
<script type="text/javascript" src="/vendor/js/jquery-ui.min.js"></script>

<form method="post" class="ui form" action="" enctype="multipart/form-data">

    <!--controls-->
    <div class="ui segment">

        <a href="/slider/admin" class="ui button">
            <i class="icon left"></i> Назад
        </a>

        <div class="ui positive submit button">
            <i class="save icon"></i> Сохранить
        </div>

        {% if model is not empty and model.getId() %}
            <a href="/slider/admin/delete/{{ model.getId() }}" class="ui button red">
                <i class="icon trash"></i> Удалить
            </a>
        {% endif %}

    </div>
    <!--end controls-->
    <div class="ui red message" style="display: none">Параметры картинок не сохранены!</div>
    <div class="ui segment">
        {{ form.renderDecorated('title') }}
        {{ form.renderDecorated('animation_speed') }}
        {{ form.renderDecorated('delay') }}
        {{ form.renderDecorated('visible') }}

        <input type="hidden" name="form" value="1">
    </div>


    <div class="ui segment">
        {{ form.render('image[]') }}
    </div>

    {% if model is defined %}
        <h3 class="ui top attached inverted teal block header">
            Работа с изображениями
        </h3>

        <div class="ui segment teal inverted attached work-with-image">

            <div class="ui stackable items gallery-item">
                {% for image in model.getRelated('SliderImages', ['order': 'sortorder ASC']) %}

                    {% set img = helper.image([
                        'id': image.id,
                        'type': 'slider',
                        'strategy': 'a',
                        'width': 250,
                        'height':150,
                        'widthHeight': true
                    ],
                    [
                        'alt': model.getTitle()|escape,
                        'data-id' : image.id
                    ]) %}
                    <div class="item" data-id="{{ image.id }}" >
                        <div class="image">
                            {{ img.imageHtml() }}
                            <a class="delete like ui corner label">
                                <i class="remove icon"></i>
                            </a>
                        </div>
                        <div class="content">
                            <div class="ui input">
                                <input name="link" id="link" placeholder="Ссылка" value="{{ image.getLink() }}">
                                <label></label>
                            </div>

                            <div style="margin-top: 10px;"></div>
                            {% set text = image.getCaption() %}
                            <textarea class="description live-edit live-input {{ helper.constant('LANG') }}-gallery"
                                      style="display: none; ">{{ image.getCaption() }}</textarea>

                            <p class="description to-edit {{  helper.constant('LANG') }}-gallery">{{ image.getCaption() }}</p>

                            <div class="button ui small red add-desc {{  helper.constant('LANG') }}-gallery" {% if text is not empty %} style="display: none" {% endif %}>
                                добавить описание
                            </div>
                        </div>
                    </div>
                {% endfor %}
            </div>
        </div>
        <h3 class="ui bottom attached inverted teal block header"></h3>


        <div class="gallery-hidden">
            {% if model.Images is not empty %}
                {% for image in model.getRelated('SliderImages', ['order':'sortorder ASC']) %}
                    {% set img = helper.image([
                    'id': image.id,
                    'type': 'slider',
                    'strategy': 'a',
                    'width': 400,
                    'height':200,
                    'widthHeight': true
                    ],
                    [
                    'data-id' : image.id
                    ]) %}
                    {{ img.imageHtml() }}
                {% endfor %}
            {% endif %}
        </div>
    {% endif %}


    <div class="ui error message"></div>
</form>

<div class="ui modal basic myModal imageDelete">
    <div class="header">
    </div>
    <div class="content">
        <div style="height: 200px">
            <h1 style="text-align: center">Вы уверенны, что хотите удалить это изображение?</h1>
            <img class="img-Delete" style="width: 300px; min-height: 150px;max-height:200px " src="" alt=""/>
        </div>
    </div>
    <div class="actions">
        <div class="two fluid ui buttons">
            <div class="ui negative labeled icon button">
                Нет
            </div>
            <div class="ui positive right labeled icon button">
                Да
            </div>
        </div>
    </div>
</div>


<!--ui semantic-->
<script>
    $('.ui.form').form({
        title: {
            identifier: 'title',
            rules: [
                {type: 'empty'}
            ]
        },
        location: {
            identifier: 'location',
            rules: [
                {type: 'empty'}
            ]
        }
    }, {
        onSuccess: function () {
            return saveGallery();
        }
    });
</script><!--/end ui semantic-->

<script>

    function saveGallery () {
        var items = {};
        var isSuccess = false;

        $('.work-with-image').find('.item').each(function () {
            items[$(this).data('id')] = {
                sort: $(this).index(),
                text: $(this).find('.live-edit.{{  helper.constant('LANG') }}-gallery').val(),
                link: $(this).find('#link').val()
            };
        });

        $.ajax({
            async: false,
            url: "/slider/admin/saveSlider?lang={{  helper.constant('LANG') }}",
            data: {
                items: items,
                slider: {% if model is defined %}{{ model.getId() }}{% else %}0{% endif %}
            },
            type: 'POST',
            success: function (data) {
                if (data === 1){
                    isSuccess = true;
                } else {
                    var message = $('.ui.red.message').show();
                }
            }
        });
        return isSuccess;
    }

    var form = $('.ui.form');
    $(document).ready(function () {

        document.lang = '{{  helper.constant('LANG') }}';
        liveEdit();

        var $element = $('.delete.corner');

        $element.on('click', function () {
            var item = $(this).parent().parent(),
                    id = item.attr('data-id'),
                    src = $('.gallery-item').find('img[data-id="' + id + '"]').attr('src');
            $('.img-Delete').attr('src', src);
            $('.ui.modal.imageDelete').modal('setting', {
                closable: true,
                onApprove: function () {
                    $.ajax({
                        url: '/slider/admin/deleteImage',
                        data: {
                            id: id
                        },
                        type: 'POST',
                        success: function (data) {

                            if (data == true) {
                                item.remove();
                            } else {
                                alert('ошибка удаления');
                            }
                        }
                    });
                }
            }).modal('show');
        });

        $('.gallery-item').sortable();
    });

</script>
