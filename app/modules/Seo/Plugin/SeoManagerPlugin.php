<?php
/**
 * @copyright Copyright (c) 2011 - 2014 Oleksandr Torosh (http://wezoom.net)
 * @author Oleksandr Torosh <web@wezoom.net>
 */

namespace Seo\Plugin;

use Phalcon\Mvc\Router;
use Phalcon\Mvc\User\Plugin;
use Phalcon\Http\Request;
use Phalcon\Mvc\Dispatcher;
use Seo\Model\Manager;

/**
 * Class SeoManagerPlugin
 * @package Seo\Plugin
 * @variable $matched_route \Seo\Model\Manager
 */
class SeoManagerPlugin extends Plugin
{

    public function __construct(Dispatcher $dispatcher, Request $request, Router $router)
    {
        $route_name = ($router->getMatchedRoute()) ? $router->getMatchedRoute()->getName() : null;

        $module = $dispatcher->getModuleName();
        $controller = $dispatcher->getControllerName();
        $action = $dispatcher->getActionName();

        $dispatcher_params = $dispatcher->getParams();
        $request_params = $request->getQuery();

        if ($route_name && !in_array($route_name, array('default', 'default_action', 'default_controller'))) {
            $query = 'route = :route: AND language = :language:';
            $manager_matched_routes = Manager::find(array(
                $query,
                'bind' => array(
                    'route' => $route_name,
                    'language' => LANG,
                ),
                'cache' => array(
                    'key' => Manager::routeCacheKey($route_name, LANG),
                    'lifetime' => 60,
                ),
            ));
            if ($manager_matched_routes) {
                foreach ($manager_matched_routes as $entry) {
                    if ($entry->getRouteParamsJson()) {
                        if ($entry->getQueryParamsJson()) {
                            $entry_route_params = json_decode($entry->getRouteParamsJson(), true);
                            $entry_query_params = json_decode($entry->getQueryParamsJson(), true);
                            if (!array_diff_assoc($entry_route_params, $dispatcher_params)) {
                                if (!array_diff_assoc($entry_query_params, $request_params)) {
                                    $this->pick($entry);
                                }
                            }
                        } else {
                            $entry_route_params = json_decode($entry->getRouteParamsJson(), true);
                            if (!array_diff_assoc($entry_route_params, $dispatcher_params)) {
                                $this->pick($entry);
                            }
                        }
                    } elseif ($entry->getQueryParamsJson()) {
                        $entry_query_params = json_decode($entry->getQueryParamsJson(), true);
                        if (!array_diff_assoc($entry_query_params, $request_params)) {
                            $this->pick($entry);
                        }
                    } else {
                        $this->pick($entry);
                    }
                }
            }
        } elseif ($module && $controller && $action) {

        }

    }

    private function pick($entry)
    {
        $helper = $this->getDI()->get('helper');
        $view = $this->getDi()->get('view');
        if ($entry->getHead_title()) {
            $helper->title()->set($entry->getHead_title());
        }
        if ($entry->getMeta_description()) {
            $helper->meta()->set('description', $entry->getMeta_description());
        }
        if ($entry->getMeta_keywords()) {
            $helper->meta()->set('keywords', $entry->getMeta_keywords());
        }
        if ($entry->getSeo_text()) {
            $view->seo_text = $entry->getSeo_text();
        }
    }

} 