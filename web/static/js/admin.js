function elFinderBrowser_3 (field_name, url, type, win) {
    var elfinder_url = '/vendor/elfinder-2.1/elfinder_tinymce_3.html';    // use an absolute path!
    tinyMCE.activeEditor.windowManager.open({
        file: elfinder_url,
        title: 'elFinder 2.0',
        width: 900,
        height: 450,
        resizable: 'yes',
        inline: 'yes',    // This parameter only has an effect if you use the inlinepopups plugin!
        popup_css: false, // Disable TinyMCE's default popup CSS
        close_previous: 'no'
    }, {
        window: win,
        input: field_name
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

function selectText(element) {
    var selection = window.getSelection();
    var range = document.createRange();
    range.selectNodeContents(element);
    selection.removeAllRanges();
    selection.addRange(range);
}