function liveEdit() {
    var $filed = $('.to-edit');

    $filed.on('click', function () {
        var langClass = '.' + document.lang + '-gallery';
        var $liveEdit = $(this).siblings('textarea' + langClass),
            $parent = $(this).parent().parent(),
            currentItem = $(this).parent().find('.to-edit' + langClass),
            height = currentItem.outerHeight() + 18;

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
            height: 110,
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
    if (confirm('Do you want to delete this image?')) {
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
