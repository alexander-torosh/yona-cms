<form method="post" class="ui form segment" action="">
    {{ form.renderDecorated('sitemap') }}
    <button type="submit" class="ui positive submit button">
        <i class="save icon"></i> Save
    </button>
</form>

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