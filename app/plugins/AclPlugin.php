<?php

/**
 * ExceptionPlugin
 * @copyright Copyright (c) 2011 - 2014 Aleksandr Torosh (http://wezoom.com.ua)
 * @author Aleksandr Torosh <webtorua@gmail.com>
 */
use Phalcon\Mvc\Dispatcher,
    Phalcon\Mvc\User\Plugin,
    \Phalcon\Mvc\View,
    Application\Acl\DefaultAcl;

class AclPlugin extends Plugin
{

    public function __construct(DefaultAcl $acl, Dispatcher $dispatcher, View $view)
    {
        $role = $this->getRole();

        $module = $dispatcher->getModuleName();
        $controller = $dispatcher->getControllerName();
        $action = $dispatcher->getActionName();

        $resourceKey = $module . '/' . $controller;
        $resourceVal = $action;

        if ($acl->isResource($resourceKey)) {
            if (!$acl->isAllowed($role, $resourceKey, $resourceVal)) {
                $this->accessDenied($role, $resourceKey, $resourceVal);
            }
        } else {
            $this->resourceNotFound($resourceKey, $view);
        }

    }

    private function getRole()
    {
        $auth = $this->session->get('auth');
        if (!$auth) {
            $role = 'guest';
        } else {
            if (isset($auth->admin_session) && $auth->admin_session) {
                $role = 'admin';
            } else {
                $role = 'member';
            }
        }

        return $role;

    }

    private function accessDenied($role, $resourceKey = null, $resourceVal = null)
    {
        echo $role . " - Access Denied to resource " . $resourceKey . '::' . $resourceVal;
        exit;
    }

    private function resourceNotFound($resourceKey, View $view)
    {
        $view->setViewsDir(__DIR__ . '/../modules/Index/views/');
        $view->setPartialsDir('');
        $view->message = "Acl resource <b>$resourceKey</b> in <b>Application\Acl\DefaultAcl</b> not exists";
        $view->partial('error/error404');

        $response = new \Phalcon\Http\Response();
        $response->setHeader(404, 'Not Found');
        $response->sendHeaders();
        echo $response->getContent();
        exit;
    }

}
