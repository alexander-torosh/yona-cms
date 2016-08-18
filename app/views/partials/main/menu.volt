<ul id="menu">
    {{ helper.menu.item( helper.translate('Home'), 'index', helper.langUrl(['for':'index']) ) }}
    {{ helper.menu.item( helper.translate('News'), 'news', helper.langUrl(['for':'publications', 'type':'news']) ) }}
    {{ helper.menu.item( helper.translate('Articles'), 'articles', helper.langUrl(['for':'publications', 'type':'articles']) ) }}
    {{ helper.menu.item( helper.translate('Contacts'), 'contacts', helper.langUrl(['for':'contacts']) ) }}
    {{ helper.menu.item( helper.translate('Admin'), null, url(['for':'admin']), ['li':['class':'last'], 'a':['class':'noajax']] ) }}
    {#
        submenu items exampple:

        {{ helper.menu.item( 'Services', 'services', langUrl(['for':'services']), [],
        [
            helper.menu.item( 'Printing', 'printing', langUrl(['for':'printing']) ),
            helper.menu.item( 'Design', 'design', langUrl(['for':'design']) )
        ]
        ) }}

    #}
</ul>