$(document).ready(function () {
    $('.ui.dropdown').dropdown(); //Админика выпадающее меню

    var link = window.location.pathname;  // Активность меню в админке
    $('.menu a[href="' + link + '"]').addClass('active');

    var requiredElements = starRequired();   //Добавление * ко всем обязательным к заполнению полям
    requiredElements.length && removeRequired(requiredElements);  // удаляем атрибут required для всех полей(меняем на валидацию семантики)

    setTimeout(function () {
        disablePreviewButton();
    }, 100);

});

function disablePreviewButton() {
    var disButton = $('.preview_lang_button.disabled');
    console.log(disButton);
    disButton.click(function () {
        return false;
    });
}

function starRequired() {
    var elements = $('input[required="required"]');
    elements.siblings('label').append('<span style="color: darkred">*</span>');
    return elements;
}

function removeRequired(elements) {
    elements.removeAttr('required');
}


function profileLogin(validation) {           // проверяем наличие емейла, предупреждаем, что если нет емейла, то профиль не войдет  в свой аккаунт
    var form = $('form');
    form.submit(function (event) {
        validation.onSuccess(function () {
            if ($('#email').val() == '') {
                event.preventDefault();
                $('.myModal').modal('setting', {
                    closable: false,
                    onDeny: function () {

                    },
                    onApprove: function () {
                        form[0].submit();
                    }
                }).modal('show');
            }
        });
    });
}


function checkPreview() {
    var ru = {
            title: $('#title').val(),
            slug: $('#slug').val(),
            preview: $('.preview_lang_button[data-lang="ru"]')
        },
        en = {
            title: $('#title_en').val(),
            slug: $('#slug_en').val(),
            preview: $('.preview_lang_button[data-lang="en"]')
        };

    langButtonAction(ru);
    langButtonAction(en);

    function langButtonAction(lang) {
        if (lang.title == '' && lang.slug == '') {
            lang.preview.addClass('disabled');
        } else {
            lang.preview.removeClass('disabled');
        }
    }
}

function liveEdit() {
    var $filed = $('.to-edit');

    $filed.on('click', function () {
        var langClass = '.' + document.lang + '-gallery';
        var $liveEdit = $(this).siblings('textarea' + langClass),
            $parent = $(this).parent().parent(),
            currentItem = $(this).parent().find('.to-edit' + langClass),
            height = currentItem.outerHeight() + 18;
        console.log('давай-давай димон! ---' + langClass);
        $liveEdit.fadeOut(3000);
        $(this).css('display', 'none');
        $liveEdit.css({
            display: 'block !important',
            height: height
        }).focus();
        $liveEdit.on('blur', function () {
            currentItem.text($liveEdit.val());
            if ($liveEdit.val() == '') {
                $(this).siblings('.add-desc').show();
            }
            outFocusFiled();
        });

        function outFocusFiled() {
            currentItem.css('display', 'block');
            $liveEdit.css('display', 'none');
        }
    });

    $('.add-desc').on('click', function () {
        var langClass = '.' + document.lang + '-gallery';
        $(this).hide().siblings('textarea' + langClass).css({
            display: 'block',
            height: 114,
            "min-height": 70
        }).focus();
        var $myLiveEdit = $(this).siblings('textarea' + langClass),
            $button = $(this);
        $(this).siblings('textarea' + langClass).blur(function () {
            $(this).siblings('.to-edit' + langClass).text($myLiveEdit.val());
            $(this).siblings('.to-edit' + langClass).css('display', 'block');
            if ($myLiveEdit.val() == '') {
                $button.show();
            }
            $myLiveEdit.css('display', 'none');
        });
    });

}

function deleteImage(id, url, type) {
    if (confirm('Удалить логотип?')) {
        $.ajax({
            url: url,
            data: {
                id: id,
                type: $('#type').val()
            },
            type: 'POST',
            success: function (data) {
                location.reload();
                //console.log(data);
            }
        });
    }
}
// Удалене изображений