<?php

namespace Dashboard;

class Router extends \Phalcon\Mvc\Router
{

    public function __construct($defaultRoutes = true)
    {
        parent::__construct($defaultRoutes);

        $this->setDefaults([
            'module' => 'index',
            'controller' => 'index',
            'action' => 'index',
        ]);

        // root route
        $this->add(
            '/',
            [
                'module' => 'index',
                'controller' => 'index',
                'action' => 'index',
            ]
        )->setName('root_path');


        $this->notFound(array(
            'module'     => 'index',
            'controller' => 'index',
            'action'     => 'error404'
        ));

        $this->mount(new \Dashboard\Index\Router());

    }

}