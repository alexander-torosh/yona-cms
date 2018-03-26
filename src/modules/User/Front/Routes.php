<?php

namespace User\Front;

use Core\Interfaces\RoutesInterface;
use Phalcon\Mvc\Router\GroupInterface;
use Phalcon\Mvc\Router\Group as RouterGroup;


class Routes implements RoutesInterface
{
    public function init(GroupInterface $group): GroupInterface
    {
        $group->addGet(
            '/',
            [
                'controller' => 'index',
                'action' => 'index',
            ]
        );

        return $group;
    }
}