<?php

/**
 * @copyright Copyright (c) 2011 - 2015 Aleksandr Torosh (http://wezoom.com.ua)
 * @author Aleksandr Torosh <webtorua@gmail.com>
 */

namespace Yona\Plugin;

use Phalcon\Exception;
use Phalcon\Http\Response;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Mvc\View;
use Phalcon\Events\Event;

class AclPlugin extends Plugin
{

    /**
     * @param Event $event
     * @param Dispatcher $dispatcher
     * @return bool
     * @throws Exception
     */
    public function beforeExecuteRoute(Event $event, Dispatcher $dispatcher)
    {
        $di = $dispatcher->getDI();
        $role = 'visitor';

        $userSession = $di->get('userSession');
        if ($userSession->has('id')) {
            $user = $di->get('helper')->getUser($userSession->id);
            $role = $user->getRole();

            if (!$this->checkStatus($user->getStatus())) {
                $this->statusError($di, $user->getStatus());
                $userSession->destroy();
                return false;
            }
        }

        $module = $dispatcher->getModuleName();
        $controller = $dispatcher->getControllerName();
        $action = $dispatcher->getActionName();

        $resourceKey = $module . '/' . $controller;
        $resourceVal = $action;

        $acl = new \Yona\Acl();
        if ($acl->isResource($resourceKey)) {
            if (!$acl->isAllowed($role, $resourceKey, $resourceVal)) {
                if ($userSession->has('id')) {
                    return $this->responseError($di, [
                        'code'    => 403,
                        'status'  => 'Access Denied',
                        'message' => 'Sorry, You have not enough permissions for this page'
                    ]);
                } else {
                    // If User URL = '/admin' redirect to Login page
                    if ($resourceKey == 'admin/index' && $resourceVal == 'index') {
                        $di->get('flashSession')->error('Your session has expired.<br>Please login below.');
                        $response = new Response();
                        $response->redirect('login');
                        $response->send();
                        return false;
                    } else {
                        return $this->responseError($di, [
                            'code'    => 401,
                            'status'  => 'Unauthorized',
                            'message' => 'Please, <a href="/login">Login</a>'
                        ]);
                    }
                }
            } else {
                // Allowed. Continue Dispatching process.
                return true;
            }
        } else {
            // Acl resource not exists
            return $this->responseError($di, [
                'code'    => 404,
                'status'  => 'Page not found',
                'message' => 'Sorry, this page can not be displayed',
                'header'  => 'Acl not found'
            ]);
        }

    }

    private function checkStatus($status)
    {
        if ($status == 'active') {
            return true;
        }
    }

    private function statusError($di, $status)
    {
        return $this->responseError($di, [
            'code'    => 403,
            'status'  => 'Access Denied',
            'message' => 'Sorry, Your account is <b>' . $status . '</b>.<br>' .
                'Please, contact us. We will try to help You!'
        ]);
    }

    private function responseError($di, $params)
    {
        die(var_dump($params));
    }

}