$(function () {

    CodeMirror.fromTextArea(document.getElementById('html'), {
        lineNumbers: true,               // показывать номера строк
        matchBrackets: true,             // подсвечивать парные скобки
        mode: "htmlmixed", // стиль подсветки
        indentUnit: 4,                    // размер табуляции
        viewportMargin: Infinity
    });

});