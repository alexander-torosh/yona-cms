<?php

/**
 * @author Oleksandr Torosh <webtorua@gmail.com>
 */
namespace Yona;

class Router extends \Phalcon\Mvc\Router
{

    public function __construct($defaultRoutes = false)
    {
        parent::__construct($defaultRoutes);

        $this->setDefaults([
            'module' => 'index',
            'controller' => 'index',
            'action' => 'index'
        ]);
    }

}