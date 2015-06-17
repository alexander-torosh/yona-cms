<?php

return [
    'database' => [
        'adapter'  => 'Mysql',
        'host'     => 'localhost',
        'username' => '',
        'password' => '',
        'dbname'   => '',
        'charset'  => 'utf8',
    ],
    'memcache' => [
        'host' => 'localhost',
        'port' => 11211,
    ],
    'cache'    => 'file', // file, memcache
];