<?php

return [

    // frontend
    'guest'      => [
        'admin/index'       => '*',
        'index/index'       => '*',
        'index/error'       => '*',
        'page/index'        => '*',
        'publication/index' => '*',
        'sitemap/index'     => '*',
        'api/index'         => '*',
        'api/page'          => '*',
    ],
    'member'     => [
        'index/index' => '*',
    ],
    // backend
    'journalist' => [
        'publication/admin'  => [
            'index',
            'add',
            'edit',
        ],
        'page/admin'         => [
            'index',
            'add',
            'edit',
        ],
        'file-manager/index' => '*',
    ],
    'editor'     => [
        'publication/admin'  => '*',
        'publication/type'   => '*',
        'cms/translate'      => '*',
        'widget/admin'       => '*',
        'file-manager/index' => '*',
        'page/admin'         => '*',
        'tree/admin'         => '*',
        'seo/sitemap'        => '*',
    ],
    'admin'      => [
        'admin/admin-user'   => '*',
        'cms/configuration'  => '*',
        'cms/translate'      => '*',
        'cms/language'       => '*',
        'cms/javascript'     => '*',
        'widget/admin'       => '*',
        'file-manager/index' => '*',
        'page/admin'         => '*',
        'publication/admin'  => '*',
        'publication/type'   => '*',
        'seo/robots'         => '*',
        'seo/sitemap'        => '*',
        'seo/manager'        => '*',
        'tree/admin'         => '*',
    ],
];