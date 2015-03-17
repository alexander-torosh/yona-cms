<ul id="menu">
    {{ helper.menu.item( helper.translate('Home'), 'index', helper.langUrl(['for':'index']) ) }}
    {{ helper.menu.item( helper.translate('News'), 'news', helper.langUrl(['for':'publications', 'type':'news']) ) }}
    {{ helper.menu.item( helper.translate('Articles'), 'articles', helper.langUrl(['for':'publications', 'type':'articles']) ) }}
    {{ helper.menu.item( helper.translate('Contacts'), 'contacts', helper.langUrl(['for':'contacts']) ) }}
    {{ helper.menu.item( helper.translate('Admin'), null, url(['for':'admin']), ['class':'noajax'] ) }}
    {#
        submenu items exampple:

        {{ helper.menu.item( helper.translate('Services'), 'services', helper.langUrl(['for':'services']), [],
        [
            helper.menu.item( helper.translate('Printing'), 'printing', helper.langUrl(['for':'printing']) ),
            helper.menu.item( helper.translate('Design'), 'design', helper.langUrl(['for':'design']) )
        ]
        ) }}

    #}
</ul>