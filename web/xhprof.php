<?php

xhprof_enable(XHPROF_FLAGS_CPU + XHPROF_FLAGS_MEMORY);

chdir(dirname(__DIR__));

define('ROOT', __DIR__);
define('HOST_HASH', substr(md5($_SERVER['HTTP_HOST']), 0, 12));

defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

define('APPLICATION_PATH', (APPLICATION_ENV == 'development') ? __DIR__ . '/../app' : __DIR__ . '/../app');
define('PUBLIC_PATH', __DIR__);

require_once APPLICATION_PATH . '/Bootstrap.php';
$bootstrap = new YonaCMS\Bootstrap();
$bootstrap->run();

$xhprof_data = xhprof_disable();

include_once "xhprof-0.9.4/xhprof_lib/utils/xhprof_lib.php";
include_once "xhprof-0.9.4/xhprof_lib/utils/xhprof_runs.php";
$xhprof_runs = new XHProfRuns_Default();
$run_id = $xhprof_runs->save_run($xhprof_data, "yona-cms");