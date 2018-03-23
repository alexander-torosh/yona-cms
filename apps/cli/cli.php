<?php
chdir(dirname(__DIR__));

define('BASE_PATH', __DIR__ . '/../../');
define('APPLICATION_PATH', __DIR__);
define('WEB_PATH', __DIR__);

// Autoloader for Composer packages
require_once BASE_PATH . '/vendor/autoload.php';

$dotenv = new Dotenv\Dotenv(BASE_PATH);
$dotenv->load();

defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

// Application class
require_once APPLICATION_PATH . '/CliKernel.php';
$app = new \Cli\CliKernel();
$app->run($argv);