<?php
 /**
 * @copyright Copyright (c) 2011 - 2014 Oleksandr Torosh (http://wezoom.net)
 * @author Oleksandr Torosh <web@wezoom.net>
 */

namespace Seo\Plugin;

use Phalcon\Mvc\User\Plugin;
use Phalcon\Http\Request;
use Phalcon\Mvc\Dispatcher;

class SeoManagerPlugin extends Plugin
{

    public function __construct(Dispatcher $dispatcher, Request $request)
    {
        var_dump($dispatcher->getModuleName(),
            $dispatcher->getControllerName(),
            $dispatcher->getActionName(),
            $dispatcher->getParams());
        var_dump($request->getQuery());
        var_dump(LANG);
        //exit;

    }

} 