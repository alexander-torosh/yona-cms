<?php
/**
 * @author Alexander Torosh <webtorua@gmail.com>
 */

namespace Api\Controllers;

use Api\ApiController;

class IndexController extends ApiController
{
    public function index()
    {
        $this->json([
            'success' => true,
            'env' => getenv('APP_ENV'),
            'DB_NAME' => getenv('DB_NAME'),
        ]);
    }
}