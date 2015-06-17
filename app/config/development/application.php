<?php

return [
    'database' => [
        'adapter' => 'Mysql',
        'host' => 'localhost',
        'username' => 'root',
        'password' => '111',
        'dbname' => 'yona-cms',
        'charset' => 'utf8',
    ],
    'memcache' => [
        'host' => 'localhost',
        'port' => 11211,
    ],
    'cache'    => 'file', // file, memcache
];