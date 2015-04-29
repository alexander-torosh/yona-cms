<div class="ui segment">

    <h3>Articles</h3>

    <ol class="sortable" id="root_articles">
        {#<li id="category_10">
            <div>Books <a href="/tree/admin/edit/1"><i class="icon edit"></i></a></div>
        </li>
        <li id="category_3">
            <div>Toys</div>
            <ol>
                <li id="category_7">
                    <div>Eco-toys</div>
                </li>
                <li id="category_20">
                    <div>Cars</div>
                </li>
            </ol>
        </li>
        <li id="category_33">
            <div>Games</div>
        </li>#}
    </ol>

    <a class="save ui button primary" data-root="articles">Save</a>
    <a href="javascript:void(0);" data-root="articles" class="add ui button positive">
        <i class="icon plus"></i> Add
    </a>

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
