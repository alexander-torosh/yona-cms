{%- macro leaf_item(leaf) %}
    {% set children = leaf.children() %}
    <li id="category_{{ leaf.getId() }}">
        <div>{{ leaf.getTitle() }}</div>
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

            {% set tree = tree_helper.tree(root) %}
            {% for leaf in tree %}
                {{ leaf_item(leaf) }}
            {% endfor %}

        </ol>

        <a class="save ui button primary" data-root="{{ root }}">Save</a>
        <a href="javascript:void(0);" data-root="{{ root }}" class="add ui button positive">
            <i class="icon plus"></i> Add
        </a>

    {% endfor %}

</div>

<link rel="stylesheet" href="/vendor/jquery-ui-1.11.4/jquery-ui.min.css"/>
<script src="/vendor/jquery-ui-1.11.4/jquery-ui.min.js"></script>
<script src="/vendor/js/jquery.mjs.nestedSortable.js"></script>

<script>
    $(function () {

        initNestedSortable();

        $('.save').click(function (e) {
            var root = $(this).data('root');
            data = $('ol.sortable#root_' + root).nestedSortable('toArray', {startDepthCount: 0});
            if (data) {
                $.post("/tree/admin/saveTree", {root: root, data: data}, function (response) {
                    if (response.success == true) {
                        noty({layout: 'center', type: 'success', text: 'Saved'});
                    }
                }, 'json');
            }

        });

        $('.add').click(function (e) {
            var root = $(this).data('root');
            var title = prompt("Enter new cateogory title", '');
            if (title != '') {
                $.post("/tree/admin/add", {root: root, title: title}, function (response) {
                    if (response.success == true) {
                        var newItemLi = $("<li>").attr('id', 'category_' + response.id);
                        var newItemDiv = $("<div>").html(title);
                        newItemLi.append(newItemDiv);

                        var list = $('ol.sortable#root_' + root);
                        list.append(newItemLi);

                        initNestedSortable();
                    }
                    if (response.error) {
                        noty({layout: 'center', type: 'error', text: response.error});
                    }
                }, 'json');
            }
        });

    });

    function initNestedSortable() {
        $('.sortable').nestedSortable({
            handle: 'div',
            items: 'li',
            toleranceElement: '> div'
        });
    }
</script>
