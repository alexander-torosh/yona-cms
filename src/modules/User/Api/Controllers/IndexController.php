<?php


namespace User\Api\Controllers;

use Core\BaseController;

class IndexController extends BaseController
{
    public function index(): array
    {
        return [
            'message' => __METHOD__,
        ];
    }
}
