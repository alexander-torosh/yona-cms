<?php
/**
 * @author Alexander Torosh <webtorua@gmail.com>
 */

require_once __DIR__ . '/../vendor/autoload.php';

use Api\ApiApplication;
use Web\WebApplication;

if (strpos($_SERVER['REQUEST_URI'], '/api') === 0) {
    $app = new ApiApplication();
} else {
    $app = new WebApplication();
}

$app->run();