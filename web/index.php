<?php

chdir(dirname(__DIR__));

defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

define('APPLICATION_PATH', (APPLICATION_ENV == 'development') ? __DIR__ . '/../app' : __DIR__ . '/../private/app' );
define('PUBLIC_PATH', __DIR__);


include APPLICATION_PATH . '/config/defines.php';

require_once APPLICATION_PATH . '/Bootstrap.php';
Bootstrap::run();