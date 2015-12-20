$(function () {

    var textareas = document.querySelectorAll('textarea');
    for (var i = 0; i < textareas.length; i++) {
        CodeMirror.fromTextArea(textareas[i], {
            lineNumbers: true,               // показывать номера строк
            matchBrackets: true,             // подсвечивать парные скобки
            mode: "htmlmixed",                  // стиль подсветки
            indentUnit: 4,                    // размер табуляции
            readOnly: true,
            viewportMargin: 10
        });
    }


});