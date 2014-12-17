<form method="post" action="" class="ui form" enctype="multipart/form-data">

    <input type="hidden" name="category_form" value="1">

    <div class="ui segment">
        <a href="/category/category" class="ui button">
            <i class="icon left"></i> Назад
        </a>
        {% if model is defined %}
            <div class="ui positive submit button">
                <i class="save icon"></i> Сохранить
            </div>
            <a href="/category/category/delete/{{ model.getId() }}" class="ui button red">
                <i class="icon trash"></i> Удалить
            </a>
        {% endif %}

    </div>

    <div class="ui error message"></div>

    <div class="ui segment">

        <div class="four fields">

            <div class="field">
                {% set types = model.getTypes() %}
                {% if model.getId() %}
                    <label>Тип</label>
                    <b>{{ types[model.getType()] }}</b>
                {% else %}
                    <label>Тип</label>
                    <div class="ui selection dropdown">
                        {{ form.renderDecorated('type') }}
                        <div class="text">{{ types[model.getType()] }}</div>
                        <i class="dropdown icon"></i>

                        <div class="menu">
                            {% for key, value in types %}
                                <div class="item" data-value="{{ key }}">{{ value }}</div>
                            {% endfor %}
                        </div>
                    </div>
                {% endif %}
            </div>

            {% if model.getId() %}

                {%- macro tree_leaf(leaf, level) %}
                    <div class="item" data-value="{{ leaf.getId() }}">{% if leaf.parent_id %}<span
                            style="width:{{ 15*(level - 1) }}px; display: inline-block; text-align: right;">
                                -&nbsp;</span>{% endif %}{{ leaf.getTitle() }}</div>
                    {% if leaf.getChildren(false).count() %}
                        {% for child in leaf.getChildren(false) %}
                            {{ tree_leaf(child, level + 1) }}
                        {% endfor %}
                    {% endif %}
                {%- endmacro %}

                <div class="field">
                    <label>Родительская категория</label>

                    <div class="ui selection dropdown">
                        {{ form.renderDecorated('parent_id') }}
                        <div class="text"></div>
                        <i class="dropdown icon"></i>

                        <div class="menu">
                            <div class="item" data-value="">-</div>
                            {% for leaf in categoriesTree %}
                                {{ tree_leaf(leaf, 1) }}
                                {#<div class="item" data-value="{{ category.getId() }}">{% if category.parent_id %}<span style="width:25px">&nbsp;</span>{% endif %}{{ category.getTitle() }}</div>#}
                            {% endfor %}
                        </div>
                    </div>
                </div>

                {{ form.renderDecorated('sortorder') }}

            {% endif %}

        </div>

        <div>

            {{ form.renderDecorated('visible') }}

            <div class="ui image">
                {% set image = helper.image([
                'id': model.getId(),
                'type': 'category',
                'width': 172,
                'height': 200,
                'strategy': 'a',
                'hash': true
                ]) %}
                {{ image.imageHtml() }}
            </div>
            {{ form.render('preview') }}

        </div>

        <div class="two fields">
            {{ form.renderDecorated('title') }}
            {{ form.renderDecorated('title_uk') }}
        </div>

        <div class="two fields">
            <div class="field">
                {{ form.renderDecorated('slug') }}
            </div>
            <div class="field">

            </div>
        </div>

        {% if model.getId() %}

            <div class="ui horizontal divider">
                Meta
            </div>

            <div class="two fields">
                {{ form.renderDecorated('meta_title') }}
                {{ form.renderDecorated('meta_title_uk') }}
            </div>
            <div class="two fields">
                {{ form.renderDecorated('meta_description') }}
                {{ form.renderDecorated('meta_description_uk') }}
            </div>
            <div class="two fields">
                {{ form.renderDecorated('meta_keywords') }}
                {{ form.renderDecorated('meta_keywords_uk') }}
            </div>

            <div class="ui horizontal divider">
                Text
            </div>

            <div class="two fields">
                {{ form.renderDecorated('text') }}
                {{ form.renderDecorated('text_uk') }}
            </div>

        {% endif %}

        <div class="ui positive submit button">
            <i class="save icon"></i> Сохранить
        </div>

    </div>

</form>

<script>
    $('.ui.form').form({
        title: {
            identifier: 'title',
            rules: [
                {
                    type: 'empty',
                    prompt: 'Укажите "Название"'
                }
            ]
        },
    });
</script>

<!--tinymce-->
<script src="/vendor/tinymce/tinymce.min.js"></script>
<script>
    tinymce.init({
        selector: "#text, #text_uk",
        theme: "modern",
        width: '100%',
        language: 'ru',
        height: 300,
        plugins: [
            "advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
            "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
            "save table contextmenu directionality emoticons template paste textcolor"
        ],
        //content_css: "",
        // links
        relative_urls: true,
        remove_script_host: false,
        // paste
        paste_as_text: true,
        paste_word_valid_elements: "p,br,b,strong,i,em",
        // spellcheck
        gecko_spellcheck: true
    });
</script>
<!--/tinymce-->