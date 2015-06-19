<form method="post" class="ui form segment" action="">
    {{ form.renderDecorated('sitemap') }}
    <button type="submit" class="ui positive submit button">
        <i class="save icon"></i> Save
    </button>
</form>

<link rel="stylesheet" href="{{ url.path() }}vendor/codemirror-4.2/lib/codemirror.css">
<script src="{{ url.path() }}vendor/codemirror-4.2/codemirror-compressed.js"></script>
<script>
    $(function () {
        var codeMirror = CodeMirror.fromTextArea(document.getElementById('sitemap'), {
            lineNumbers: true,               // показывать номера строк
            matchBrackets: true,             // подсвечивать парные скобки
            mode: "htmlmixed", // стиль подсветки
            indentUnit: 4,                    // размер табуляции
            viewportMargin: Infinity
        });
    });
</script>