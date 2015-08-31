<?php

/**
 * DefaultAcl
 * @copyright Copyright (c) 2011 - 2015 Aleksandr Torosh (http://wezoom.com.ua)
 * @author Aleksandr Torosh <webtorua@gmail.com>
 */

namespace YonaCMS\Plugin;

use Phalcon\Mvc\Dispatcher,
    Phalcon\Mvc\User\Plugin,
    Phalcon\Mvc\View,
    Application\Acl\DefaultAcl;

class Acl extends Plugin
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
                $this->accessDenied($role, $resourceKey, $resourceVal, $view);
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
            if ($auth->admin_session == true) {
                $role = \Admin\Model\AdminUser::getRoleById($auth->id);
            } else {
                $role = 'member';
            }
        }
        return $role;

    }

    private function accessDenied($role, $resourceKey = null, $resourceVal = null, View $view)
    {
        if (in_array($role, ['guest', 'member'])) {
            return $this->redirect('/admin');
        }

        $view->setViewsDir(__DIR__ . '/../modules/Index/views/');
        $view->setPartialsDir('');
        $view->message = "$role - Access Denied to resource <b>$resourceKey::$resourceVal</b>";
        $view->partial('error/error403');

        $response = new \Phalcon\Http\Response();
        $response->setHeader(403, 'Forbidden');
        $response->sendHeaders();
        echo $response->getContent();
        exit;
    }

    private function resourceNotFound($resourceKey, View $view)
    {
        $view->setViewsDir(__DIR__ . '/../modules/Index/views/');
        $view->setPartialsDir('');
        $view->message = "Acl resource <b>$resourceKey</b> in <b>/app/config/acl.php</b> not exists";
        $view->partial('error/error404');
        $response = new \Phalcon\Http\Response();
        $response->setHeader(404, 'Not Found');
        $response->sendHeaders();
        echo $response->getContent();
        exit;
    }

    private function redirect($url, $code = 302)
    {
        switch ($code) {
            case 301 :
                header('HTTP/1.1 301 Moved Permanently');
                break;
            case 302 :
                header('HTTP/1.1 302 Moved Temporarily');
                break;
        }
        header('Location: ' . $url);
        exit;
    }

}