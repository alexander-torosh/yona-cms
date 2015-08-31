$(function () {

    initNestedSortable();

    $('.save').click(function (e) {
        var root = $(this).data('root');
        data = $('ol.sortable#root_' + root).nestedSortable('toArray', {startDepthCount: 0});
        if (data) {
            $.post("/tree/admin/saveTree", {root: root, data: data}, function (response) {
                if (response.success == true) {
                    noty({layout: 'center', type: 'success', text: 'Root "'+root+'" saved', timeout: 2000});
                }
            }, 'json');
        }

    });

    $('.add').click(function (e) {
        var root = $(this).data('root');
        var title = prompt("Enter new cateogory title", '');
        console.log(title);
        if (title) {
            $.post("/tree/admin/add", {root: root, title: title}, function (response) {
                if (response.success == true) {
                    var newItemLi = $("<li>").attr('id', 'category_' + response.id);
                    var newItemDiv = $("<div>").addClass('item');

                    var title = $("<span>").addClass('title').html(response.title);
                    var info = $("<span>").addClass('info').html('(' + response.slug + ')');
                    var edit = $("<a>").attr('href', '/tree/admin/edit/' + response.id)
                        .html('<i class="icon edit"></i>');
                    var del = $("<a>").attr('href', 'javascript:void(0);')
                        .attr('onclick', 'deleteCategory(' + response.id + ', this)')
                        .addClass('delete')
                        .html('<i class="icon trash"></i>');

                    newItemDiv.append(title).append(info).append(edit).append(del);

                    newItemLi.append(newItemDiv);

                    var list = $('ol.sortable#root_' + root);
                    list.append(newItemLi);

                    initNestedSortable();
                    $("#save-root-" + root).click();
                }
                if (response.error) {
                    noty({layout: 'center', type: 'error', text: response.error, timeout: 2000});
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

function deleteCategory(category_id, node) {
    if (confirm('Do you really want delete this category?')) {
        $.post('/tree/admin/delete', {category_id: category_id}, function (response) {
            if (response.success) {
                var parent = node.parentNode.parentNode;
                if (parent) {
                    parent.parentNode.removeChild(parent);
                    initNestedSortable();
                    $("#save-root-" + response.root).click();
                }
            }
        });
    }
}