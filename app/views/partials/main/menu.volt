<ul id="menu">
    {{ helper.menu.item( translate('Home'), 'index', langUrl(['for':'index']) ) }}
    {{ helper.menu.item( translate('News'), 'news', langUrl(['for':'publications', 'type':'news']) ) }}
    {{ helper.menu.item( translate('Articles'), 'articles', langUrl(['for':'publications', 'type':'articles']) ) }}
    {{ helper.menu.item( translate('Contacts'), 'contacts', langUrl(['for':'contacts']) ) }}
    {{ helper.menu.item( translate('Admin'), null, url(['for':'admin']), ['li':['class':'last'], 'a':['class':'noajax']] ) }}
    {#
        submenu items exampple:

        {{ helper.menu.item( translate('Services'), 'services', langUrl(['for':'services']), [],
        [
            helper.menu.item( translate('Printing'), 'printing', langUrl(['for':'printing']) ),
            helper.menu.item( translate('Design'), 'design', langUrl(['for':'design']) )
        ]
        ) }}

    #}
</ul>