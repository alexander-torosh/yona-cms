function elFinderBrowser (field_name, url, type, win) {
    tinymce.activeEditor.windowManager.open({
        file: '/vendor/elfinder-2.1/elfinder_tinymce.html',// use an absolute path!
        title: 'elFinder 2.0',
        width: 900,
        height: 450,
        resizable: 'yes'
    }, {
        setUrl: function (url) {
            console.log(url);
            win.document.getElementById(field_name).value = url.url;
        }
    });
    return false;
}

$(function() {

    $('.ui.checkbox').checkbox();

    $('.ui.dropdown').dropdown();

    $('.ui.selection.dropdown').dropdown({
        duration: 10
    });

    $('.ui.menu.init .item').tab();

    $('[data-description]').each(function(index, element){
        var description = $(element).attr('data-description');
        var descriptionElement = $('<div class="description">');
        descriptionElement.html(description);
        $(element).after(descriptionElement);
    });

});