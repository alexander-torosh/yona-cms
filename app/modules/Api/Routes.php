<?php

namespace Api;

class Routes
{

    public function init($router)
    {
        $router->add('/api', array(
            'module'     => 'api',
            'controller' => 'index',
            'action'     => 'index',
        ))->setName('api');

        return $router;
    }
}
