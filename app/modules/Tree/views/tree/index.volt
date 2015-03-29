<link rel="stylesheet" href="/vendor/jquery-ui-1.11.4/jquery-ui.min.css" />
<script src="/vendor/jquery-ui-1.11.4/jquery-ui.min.js"></script>
<script src="/vendor/js/jquery.mjs.nestedSortable.js"></script>

<ol class="sortable">
    <li><div>Some content</div></li>
    <li>
        <div>Some content</div>
        <ol>
            <li><div>Some sub-item content</div></li>
            <li><div>Some sub-item content</div></li>
        </ol>
    </li>
    <li><div>Some content</div></li>
</ol>

<script>
    $(document).ready(function(){

        $('.sortable').nestedSortable({
            handle: 'div',
            items: 'li',
            toleranceElement: '> div'
        });

    });
</script>