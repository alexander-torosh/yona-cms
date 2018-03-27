<?php

namespace User\Api;

use Core\Interfaces\CollectionsInterface;
use User\Api\Controllers\IndexController;

class Collections implements CollectionsInterface
{
    public function init(): array
    {
        return [
            IndexController::class => [
                '/index' => [self::METHOD_GET => 'index']
            ]
        ];
    }
}