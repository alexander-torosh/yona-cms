<?php
chdir(dirname(__DIR__));

\define('BASE_PATH', __DIR__ . '/../');
\define('SRC_PATH', BASE_PATH . 'src/');
\define('APP_PATH', SRC_PATH . 'app/');
\define('MODULES_PATH', SRC_PATH . 'modules/');
\define('CONFIG_PATH', APP_PATH . 'config/');

// Autoloader for Composer packages
require_once BASE_PATH . 'vendor/autoload.php';

$dotenv = new \Dotenv\Dotenv(BASE_PATH);
$dotenv->load();

\defined('APPLICATION_ENV') ||
\define('APPLICATION_ENV', getenv('APPLICATION_ENV') ?: 'production');

\define('IMAGES_SERVER', getenv('IMAGE_SERVER'));
\define('STATIC_SERVER', getenv('STATIC_SERVER'));

//Debug
if (APPLICATION_ENV !== 'production') {
    ini_set('display_errors', 'On');
    ini_set('display_startup_errors', 'On');
    ini_set('error_reporting', 'E_ALL');

    $debug = new \Phalcon\Debug();
    $debug->listen();
}

// Application class
$manager = new \Application\KernelManager($_ENV);
$manager->setKernel(new \Application\Web\WebKernel());

$manager->handle();

