<?php

return [
    'db' => [
        'host'     => 'yona-mysql',
        'port'     => 3306,
        'username' => getenv('MYSQL_USER'),
        'password' => getenv('MYSQL_PASSWORD'),
        'dbname'   => getenv('MYSQL_DATABASE'),
        'charset'  => 'utf8mb4'
    ],

    'redis' => [
        'host'      => 'yona-redis',
        'port'      => 6379,
        'persistent'=> 'RDB',
        'lifetime'  => 86000,
        'prefix'    => '',
    ],

    'defaults' => [
        'session_adapter' => 'redis',
        'cache_adapter'   => 'redis',
    ],

    'images_server' => '//localhost:8083',
    'static_server' => '//localhost:8082',

];