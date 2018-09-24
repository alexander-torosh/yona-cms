<?php

namespace User\Web;

use Core\Interfaces\RoutesInterface;
use Phalcon\Mvc\Router\GroupInterface;

class Routes implements RoutesInterface
{
    public function init(GroupInterface $group, $moduleName): GroupInterface
    {
        $group->setPrefix('/user');

        $group->addGet('/login', [
            'controller' => 'login',
            'action' => 'index',
        ]);

        return $group;
    }
}
