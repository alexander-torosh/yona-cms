<?php

// default config for production environment

$config = [
    'db' => [
        'host'     => '127.0.0.1',
        'port'     => 3306,
        'username' => getenv('MYSQL_USER'),
        'password' => getenv('MYSQL_PASS'),
        'dbname'   => getenv('MYSQL_DATABASE'),
        'charset'  => 'utf8mb4'
    ],

    'redis' => [
        'host'      => '127.0.0.1',
        'port'      => 6379,
        'persistent'=> 'RDB',
        'lifetime'  => 86000,
        'prefix'    => '',
    ],

    'defaults' => [
        'session_adapter' => 'files',
        'cache_adapter'   => 'files',
    ],

    'images_server' => '//images.yonacms.com',
    'static_server' => '//static.yonacms.com',

];

// include environment configuration
// development
if (APPLICATION_ENV === 'development') {

    $development = include __DIR__ . '/environment/development.php';
    $config = array_replace_recursive($config, $development);
}

return $config;