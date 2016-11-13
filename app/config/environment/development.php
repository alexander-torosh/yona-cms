<?php

return [
    'base_path' => '/',
    //'base_path' => 'http://localhost/yona-cms/web/',

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

    'memcached'  => [
        'host' => 'localhost',
        'port' => 11211,
    ],

    'cache'     => 'file', // memcache, memcached
];