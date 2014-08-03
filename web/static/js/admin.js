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