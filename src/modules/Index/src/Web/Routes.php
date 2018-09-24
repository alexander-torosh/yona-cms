<?php

namespace Index\Web;

use Core\Interfaces\RoutesInterface;
use Phalcon\Mvc\Router\GroupInterface;

class Routes implements RoutesInterface
{
    public function init(GroupInterface $group, $moduleName): GroupInterface
    {
        // All the routes start with the module name
        $group->setPrefix('/');

        $group->addGet('', [
            'controller' => 'index',
            'action' => 'index',
        ]);

        return $group;
    }
}
