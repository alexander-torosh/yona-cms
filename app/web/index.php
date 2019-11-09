<?php
/**
 * @author Alexander Torosh <webtorua@gmail.com>
 */

require_once __DIR__ . './../../vendor/autoload.php';

// URL starts from `/api`
if (strpos($_SERVER['REQUEST_URI'], '/api') === 0) {
    $app = new \Api\ApiApplication();
} else {
    $app = new \Web\WebApplication();
}

$app->run();