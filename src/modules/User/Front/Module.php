<?php

namespace User\Front;

use Core\Interfaces\MicroModuleInterface;
use Core\MicroAbstract;

class Module implements MicroModuleInterface
{
    public function initialize(MicroAbstract $app): void
    {
        /**
         * Setting up the view component
         * @var \Core\View\View $view
         */
        $view = $app->getDI()->get('view');
        $view->setViewsDir(__DIR__ . '/Views/');
    }
}
