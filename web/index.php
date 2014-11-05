<?php

// Костыль для переадресаций index.php, index.html на соотв. роуты, пример: '/index.php/news' -> '/news'
// Данное решение было сделано для облегчения настройки веб-сервера и выполнения SEO-требований
if (strpos($_SERVER['REQUEST_URI'], 'index.php') || strpos($_SERVER['REQUEST_URI'], 'index.html')) {
    header('HTTP/1.0 301 Moved Permanently');
    $replaced_url = str_replace(
        array('index.php/', 'index.php', 'index.html'),
        array('', '', ''),
        str_replace('?', '', $_SERVER['REQUEST_URI'])
    );
    header('Location: http://' . $_SERVER['HTTP_HOST'] . $replaced_url);
    exit;
}

chdir(dirname(__DIR__));

define('ROOT', __DIR__);
define('HOST_HASH', substr(md5($_SERVER['HTTP_HOST']), 0, 12));

defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

define('APPLICATION_PATH', (APPLICATION_ENV == 'development') ? __DIR__ . '/../app' : __DIR__ . '/../private/app');
define('PUBLIC_PATH', __DIR__);

require_once APPLICATION_PATH . '/Bootstrap.php';
$bootstrap = new Bootstrap();
$bootstrap->run();
