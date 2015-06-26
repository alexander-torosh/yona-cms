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
use Phalcon\Mvc\View;
use Seo\Model\Manager;

/**
 * Class SeoManagerPlugin
 * @package Seo\Plugin
 * @variable $matched_route \Seo\Model\Manager
 */
class SeoManager extends Plugin
{

    public function __construct(Dispatcher $dispatcher, Request $request, Router $router, View $view)
    {
        if ($view->getLayout() == 'admin') {
            return;
        }

        $route_name = ($router->getMatchedRoute()) ? $router->getMatchedRoute()->getName() : null;

        $module = $dispatcher->getModuleName();
        $controller = $dispatcher->getControllerName();
        $action = $dispatcher->getActionName();

        $dispatcher_params = $dispatcher->getParams();
        $request_params = $request->getQuery();
        unset($request_params['_url']);

        $match_url_entry = $this->matchingUrl($request->getURI());

        if ($match_url_entry) {
            $this->pick($match_url_entry);
        } elseif ($route_name && !in_array($route_name, ['default', 'default_action', 'default_controller'])) {
            if (!$this->matchingRoute($route_name, $dispatcher_params, $request_params)) {
                if ($module && $controller && $action) {
                    $this->matchingMCA($module, $controller, $action, $dispatcher_params, $request_params);
                }
            }
        } elseif ($module && $controller && $action) {
            $this->matchingMCA($module, $controller, $action, $dispatcher_params, $request_params);
        }
    }

    private function matchingUrl($url)
    {
        return Manager::findFirst([
            'url = :url:',
            'bind'  => [
                'url' => $url,
            ],
            'cache' => [
                'key'      => Manager::urlCacheKey($url),
                'lifetime' => 60,
            ],
        ]);
    }

    private function matchingRoute($route_name, $dispatcher_params, $request_params)
    {
        $query = 'route_ml = :route: AND language = :language:';
        $manager_matched_routes = Manager::find([
            $query,
            'bind'  => [
                'route'    => $route_name,
                'language' => LANG,
            ],
            'cache' => [
                'key'      => Manager::routeCacheKey($route_name, LANG),
                'lifetime' => 60,
            ],
        ]);
        if ($manager_matched_routes) {
            foreach ($manager_matched_routes as $entry) {
                if ($this->match($entry, $dispatcher_params, $request_params)) {
                    return true;
                }
            }
        }
        return false;
    }

    private function matchingMCA($module, $controller, $action, $dispatcher_params, $request_params)
    {
        $query = 'module = :module: AND controller = :controller: AND action = :action: AND language = :language:';
        $manager_matched_routes = Manager::find([
            $query,
            'bind'  => [
                'module'     => $module,
                'controller' => $controller,
                'action'     => $action,
                'language'   => LANG,
            ],
            'cache' => [
                'key'      => Manager::mcaCacheKey($module, $controller, $action, LANG),
                'lifetime' => 60,
            ],
        ]);
        if ($manager_matched_routes) {
            foreach ($manager_matched_routes as $entry) {
                if ($this->match($entry, $dispatcher_params, $request_params)) {
                    return true;
                }
            }
        }
    }

    private function match($entry, $dispatcher_params, $request_params)
    {
        if ($entry->getRouteParamsJson()) {
            if ($entry->getQueryParamsJson()) {
                $entry_route_params = json_decode($entry->getRouteParamsJson(), true);
                $entry_query_params = json_decode($entry->getQueryParamsJson(), true);
                if (!array_diff_assoc($entry_route_params, $dispatcher_params)) {
                    if (!array_diff_assoc($entry_query_params, $request_params)) {
                        return $this->pick($entry);
                    }
                }
            } else {
                if (!empty($request_params)) {
                    return;
                }
                $entry_route_params = json_decode($entry->getRouteParamsJson(), true);
                if (!array_diff_assoc($entry_route_params, $dispatcher_params)) {
                    return $this->pick($entry);
                }
            }
        } elseif ($entry->getQueryParamsJson()) {
            $entry_query_params = json_decode($entry->getQueryParamsJson(), true);
            if (!array_diff_assoc($entry_query_params, $request_params)) {
                return $this->pick($entry);
            }
        } else {
            return $this->pick($entry);
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
        $helper->meta()->set('seo-manager', 'matched');
        return true;
    }

} 