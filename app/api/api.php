<?php
/**
 * @author Alexander Torosh <webtorua@gmail.com>
 */

require_once __DIR__ . './../../vendor/autoload.php';

use \Api\ApiApplication;

$app = new ApiApplication();
$app->run();