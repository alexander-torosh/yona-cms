<?php

namespace User\Front;

use Core\Interfaces\CollectionsInterface;
use Phalcon\Mvc\Micro\Collection as MicroCollection;
use User\Front\Controllers\IndexController;

class Collections implements CollectionsInterface
{
    /**
     * @return MicroCollection[]
     */
    public function collections(): array
    {
        // IndexController
        $index = new MicroCollection();
        $index->setHandler(IndexController::class, true);
        // common prefix for all routes
        $index->setPrefix('/user');
        $index->get('/', 'index');

        return [$index];
    }
}
