<?php


namespace User\Api\Controllers;

use Core\ControllerAbstract;

class IndexControllerAbstract extends ControllerAbstract
{
    public function index(): array
    {
        return [
            'message' => __METHOD__,
        ];
    }
}
