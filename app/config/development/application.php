<?php

return [
    'base_path' => 'http://localhost/yona-cms/web/',
    //'base_path' => '/',

    'database'  => [
        'adapter'  => 'Mysql',
        'host'     => 'localhost',
        'username' => 'root',
        'password' => '111',
        'dbname'   => 'yona-cms',
        'charset'  => 'utf8',
    ],

    'memcache'  => [
        'host' => 'localhost',
        'port' => 11211,
    ],

    'cache'     => 'file',
    //'cache'     => 'memcache',
];