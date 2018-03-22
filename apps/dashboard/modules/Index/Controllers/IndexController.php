<?php

namespace Dashboard\Index\Controllers;

use Dashboard\ControllerAbstract;

class IndexController extends ControllerAbstract
{
    public function indexAction()
    {
        echo __METHOD__;
    }

    public function error404Action()
    {

    }
}