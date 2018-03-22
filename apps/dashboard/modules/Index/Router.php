<?php


namespace Dashboard\Index;


use Phalcon\Mvc\Router\Group;

class Router extends Group
{
    public function initialize()
    {
        // Default paths
        $this->setPaths(
            [
                'module'    => 'index',
                'namespace' => 'Index\\Controllers',
            ]
        );

        // All the routes start with /index
        $this->setPrefix('/index');

        // Add a route to the group
        $this->add(
            '/',
            [
                'controller' => 'index',
                'action' => 'index',
            ]
        );
    }
}