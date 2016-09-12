<?php

namespace Api;

class Module
{

    public function registerAutoloaders()
    {

    }

    public function registerServices($di)
    {
        $dispatcher = $di->get('dispatcher');
        $dispatcher->setDefaultNamespace("Api\Controller");
        $di->set('dispatcher', $dispatcher);

    }

}