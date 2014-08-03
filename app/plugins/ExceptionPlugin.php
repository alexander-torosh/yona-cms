<?php

/**
 * ExceptionPlugin
 * @copyright Copyright (c) 2011 - 2014 Aleksandr Torosh (http://wezoom.com.ua)
 * @author Aleksandr Torosh <webtorua@gmail.com>
 */
use Phalcon\Mvc\Dispatcher,
    Phalcon\Mvc\Dispatcher\Exception as DispatchException,
    Phalcon\Mvc\User\Plugin;

class ExceptionPlugin extends Plugin
{

    public function __construct(Dispatcher $dispatcher, $exception)
    {
        if ($exception instanceof DispatchException) {
            $dispatcher->forward(array(
                'module'     => 'index',
                'controller' => 'error',
                'action'     => 'error404'
            ));
            return false;
        }

        $dispatcher->forward(array(
            'module'     => 'index',
            'controller' => 'error',
            'action'     => 'error503'
        ));

        return false;

    }

}
