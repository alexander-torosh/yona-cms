{%- macro leaf_item(leaf, url) %}
    <li id="category_{{ leaf.getId() }}">
        <div class="item">
            <span class="title">{{ leaf.getTitle() }}</span>
            <span class="info">({{ leaf.getSlug() }})</span>
            <a href="{{ url }}tree/admin/edit/{{ leaf.getId() }}?lang={{ constant('LANG') }}"><i class="icon edit"></i></a>
            <a href="javascript:void(0);" onclick="deleteCategory({{ leaf.getId() }}, this)" class="delete"><i class="icon trash"></i></a>
        </div>
        {% if leaf.hasChildren() %}
            <ol>
                {% for child in leaf.children() %}
                    {{ leaf_item(child, url) }}
                {% endfor %}
            </ol>
        {% endif %}
    </li>
{%- endmacro %}

<div class="ui blue segment">
    {{ helper.at('You can drag and drop tree elements to change order and relations') }}
</div>

<div class="ui segment">

    {% for root, root_title in roots %}
        <h3>{{ root_title }}</h3>

        <ol class="sortable" id="root_{{ root }}">

            {% set tree = tree_helper.treeUpperLeafs(root) %}
            {% for leaf in tree %}
                {{ leaf_item(leaf, url.get()) }}
            {% endfor %}

        </ol>

        <a class="save ui button primary" id="save-root-{{ root }}" data-root="{{ root }}">
            <i class="save icon"></i> {{ helper.at('Save') }}
        </a>
        <a href="javascript:void(0);" data-root="{{ root }}" class="add ui button positive">
            <i class="icon plus"></i> {{ helper.at('Add') }}
        </a>

    {% endfor %}

</div>

<link rel="stylesheet" href="{{ url.path() }}vendor/jquery-ui-1.11.4/jquery-ui.min.css">
<script src="{{ url.path() }}vendor/jquery-ui-1.11.4/jquery-ui.min.js"></script>
<script src="{{ url.path() }}vendor/js/jquery.mjs.nestedSortable.js"></script>