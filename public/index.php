<?php
chdir(dirname(__DIR__));

define('BASE_PATH', __DIR__ . '/../');
define('APPLICATION_PATH', __DIR__ . '/../apps');
define('WEB_PATH', __DIR__);

// Autoloader for Composer packages
require_once BASE_PATH . '/vendor/autoload.php';

$dotenv = new Dotenv\Dotenv(BASE_PATH);
$dotenv->load();

defined('APPLICATION_ENV') ||
define('APPLICATION_ENV', getenv('APPLICATION_ENV') ? : 'production');

//Debug
if (APPLICATION_ENV !== 'production') {
    $debug = new \Phalcon\Debug();
    $debug->listen();
}

// Application class
require_once APPLICATION_PATH . '/Kernel.php';
$app = new \Dashboard\Kernel();
$app->run();