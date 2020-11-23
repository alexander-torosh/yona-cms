<?php
/**
 * @author Alexander Torosh <webtorua@gmail.com>
 */
require_once __DIR__.'/../vendor/autoload.php';

use Api\Application as ApiApplication;
use Web\Application as WebApplication;

if (0 === strpos($_SERVER['REQUEST_URI'], '/api')) {
    $app = new ApiApplication();
} else {
    $app = new WebApplication();
}

$app->run();
