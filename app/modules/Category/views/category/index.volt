{%- macro tree_leaf(leaf) %}
    <li>
        <a href="/category/category/edit/{{ leaf.getId() }}"{% if not leaf.getVisible() %} class="grey"{% endif %}>{{ leaf.getTitle() }}</a> <small class="grey">({{ leaf.sortorder }})</small>
        {% if leaf.getChildren(false).count() %}
            <ol>
            {% for child in leaf.getChildren(false) %}
                {{ tree_leaf(child) }}
            {% endfor %}
            </ol>
        {% endif %}
    </li>
{%- endmacro %}

<p><a href="/category/category/add" class="ui positive button"><i class="add icon"></i> Добавить</a></p>

{% for type, type_title in model.getTypes() %}
    <h3>{{ type_title }}</h3>
    {% set tree = model.getCategoriesTreeByType(type, null, false) %}
    {% if tree %}
        <ol class="ui list">
        {% for leaf in tree %}
            {{ tree_leaf(leaf) }}
        {% endfor %}
        </ol>
    {% endif %}
{% endfor %}