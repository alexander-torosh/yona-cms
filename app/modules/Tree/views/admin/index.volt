{%- macro leaf_item(leaf) %}
    {% set children = leaf.children() %}
    <li id="category_{{ leaf.getId() }}">
        <div class="item">
            <span class="title">{{ leaf.getTitle() }}</span>
            <span class="info">({{ leaf.getSlug() }})</span>
            <a href="/tree/admin/edit/{{ leaf.getId() }}"><i class="icon edit"></i></a>
            <a href="javascript:void(0);" onclick="deleteCategory({{ leaf.getId() }}, this)" class="delete"><i class="icon trash"></i></a>
        </div>
        {% if children.count() %}
            <ol>
                {% for child in children %}
                    {{ leaf_item(child) }}
                {% endfor %}
            </ol>
        {% endif %}
    </li>
{%- endmacro %}

<div class="ui segment">

    {% for root, root_title in roots %}
        <h3>{{ root_title }}</h3>

        <ol class="sortable" id="root_{{ root }}">

            {% set tree = tree_helper.treeUpperLeafs(root) %}
            {% for leaf in tree %}
                {{ leaf_item(leaf) }}
            {% endfor %}

        </ol>

        <a class="save ui button primary" id="save-root-{{ root }}" data-root="{{ root }}">Save</a>
        <a href="javascript:void(0);" data-root="{{ root }}" class="add ui button positive">
            <i class="icon plus"></i> Add
        </a>

    {% endfor %}

</div>

<link rel="stylesheet" href="/vendor/jquery-ui-1.11.4/jquery-ui.min.css"/>
<script src="/vendor/jquery-ui-1.11.4/jquery-ui.min.js"></script>
<script src="/vendor/js/jquery.mjs.nestedSortable.js"></script>