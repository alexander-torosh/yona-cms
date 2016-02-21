<?php

namespace Index;

use Phalcon\DiInterface;
use Phalcon\Mvc\ModuleDefinitionInterface;

class Module implements ModuleDefinitionInterface
{

    public function registerAutoloaders(DiInterface $di = null)
    {

    }

    public function registerServices(DiInterface $di)
    {
        $di->get('dispatcher')->setDefaultNamespace('Index\Controller');
        $di->get('view')->setViewsDir(__DIR__ . '/views/');
    }

}