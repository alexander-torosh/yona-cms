<?php

return [
    'base_path' => '/',
    //'base_path' => 'http://localhost/yona-cms/web/',

    'database'  => [
        'adapter'  => 'Mysql',
        'host'     => 'localhost',
        'username' => '',
        'password' => '',
        'dbname'   => '',
        'charset'  => 'utf8',
    ],

    'memcached'  => [
        'host' => 'localhost',
        'port' => 11211,
    ],

    //'cache'     => 'file',
    'cache'     => 'memcached',

    //'modelsMetadata' => 'memory',
    'modelsMetadata' => 'memcached'
];