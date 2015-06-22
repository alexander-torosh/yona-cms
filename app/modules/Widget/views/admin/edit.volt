<div class="ui segment">
    <a href="{{ url.get() }}widget/admin/index" class="ui button">
        <i class="icon left arrow"></i> Back
    </a>
    {% if widget is defined %}
        <a href="{{ url.get() }}widget/admin/delete/{{ widget.getId() }}" class="ui button red">
            <i class="icon trash"></i> Delete
        </a>
    {% endif %}
</div>

<form method="post" action="" class="ui form segment">
    {% if widget is defined %}
        <p>ID = <b><?php echo $widget->getId() ?></b></p>
    {% else %}
        {{ form.renderDecorated('id') }}
    {% endif %}
    {{ form.renderDecorated('title') }}
    {{ form.renderDecorated('html') }}

    <div class="ui positive submit button">
        <i class="save icon"></i> Save
    </div>
</form>

<script>
    $('.ui.form').form({});
</script>

<link rel="stylesheet" href="{{ url.get() }}vendor/codemirror-4.2/lib/codemirror.css">
<script src="{{ url.get() }}vendor/codemirror-4.2/codemirror-compressed.js"></script>

<script>
    $(function () {
        CodeMirror.fromTextArea(document.getElementById('html'), {
            lineNumbers: true,               // показывать номера строк
            matchBrackets: true,             // подсвечивать парные скобки
            mode: "htmlmixed", // стиль подсветки
            indentUnit: 4,                    // размер табуляции
            viewportMargin: Infinity
        });
    });
</script>