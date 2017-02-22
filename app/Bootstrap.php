<?php

namespace YonaCMS;

use Yona\Cache\Manager as CacheManager;

/**
 * Bootstrap
 * @copyright Copyright (c) 2011 - 2014 Aleksandr Torosh (http://wezoom.com.ua)
 * @author Aleksandr Torosh <webtorua@gmail.com>
 */
class Bootstrap
{

    public function run()
    {
        $di = new \Phalcon\DI\FactoryDefault();

        // Config
        require_once APPLICATION_PATH . '/modules/Cms/Config.php';
        $config = \Cms\Config::get();
        $di->set('config', $config);

        // Registry
        $registry = new \Phalcon\Registry();
        $di->set('registry', $registry);

        // Loader
        $loader = new \Phalcon\Loader();
        $loader->registerNamespaces($config->loader->namespaces->toArray());
        $loader->registerDirs([APPLICATION_PATH . "/plugins/"]);
        $loader->register();
        require_once APPLICATION_PATH . '/../vendor/autoload.php';

        // Database
        $db = new \Phalcon\Db\Adapter\Pdo\Mysql([
            "host"     => $config->database->host,
            "username" => $config->database->username,
            "password" => $config->database->password,
            "dbname"   => $config->database->dbname,
            "charset"  => $config->database->charset,
        ]);
        $di->set('db', $db);

        // View
        $this->initView($di);

        // URL
        $url = new \Phalcon\Mvc\Url();
        $url->setBasePath($config->base_path);
        $url->setBaseUri($config->base_path);
        $di->set('url', $url);

        // Cache
        $this->initCache($di);

        // Application
        $application = new \Phalcon\Mvc\Application();
        $application->registerModules($config->modules->toArray());

        // Events Manager, Dispatcher
        $this->initEventManager($di);

        // Session
        $session = new \Phalcon\Session\Adapter\Files();
        $session->start();
        $di->set('session', $session);

        $acl = new \Yona\Acl\DefaultAcl();
        $di->set('acl', $acl);

        // Flash helper
        $flash = new \Phalcon\Flash\Session([
            'error'   => 'ui red inverted segment',
            'success' => 'ui green inverted segment',
            'notice'  => 'ui blue inverted segment',
            'warning' => 'ui orange inverted segment',
        ]);
        $di->set('flash', $flash);

        $di->set('helper', new \Yona\Mvc\Helper());

        // Routing
        $this->initRouting($application, $di);

        $application->setDI($di);

        // Main dispatching process
        $this->dispatch($di);

    }

    /**
     * @param \Phalcon\Mvc\Application $application
     * @param \Phalcon\Di\FactoryDefault $di
     */
    private function initRouting($application, $di)
    {
        $router = new \Yona\Mvc\Router\DefaultRouter();
        $router->setDi($di);
        foreach ($application->getModules() as $module) {
            $routesClassName = str_replace('Module', 'Routes', $module['className']);
            if (class_exists($routesClassName)) {
                $routesClass = new $routesClassName();
                $router      = $routesClass->init($router);
            }
            $initClassName = str_replace('Module', 'Init', $module['className']);
            if (class_exists($initClassName)) {
                new $initClassName();
            }
        }
        $di->set('router', $router);
    }

    /**
     * @param \Phalcon\Di\FactoryDefault $di
     */
    private function initEventManager($di)
    {
        $eventsManager = new \Phalcon\Events\Manager();
        $dispatcher    = new \Phalcon\Mvc\Dispatcher();

        $eventsManager->attach("dispatch:beforeDispatchLoop", function ($event, $dispatcher) use ($di) {
            new \YonaCMS\Plugin\CheckPoint($di->get('request'));
            new \YonaCMS\Plugin\Localization($dispatcher);
            new \YonaCMS\Plugin\AdminLocalization($di->get('config'));
            new \YonaCMS\Plugin\Acl($di->get('acl'), $dispatcher, $di->get('view'));
        });

        $eventsManager->attach("dispatch:afterDispatchLoop", function ($event, $dispatcher) use ($di) {
            new \Seo\Plugin\SeoManager($dispatcher, $di->get('request'), $di->get('router'), $di->get('view'));
            new \YonaCMS\Plugin\Title($di);
        });

        // Profiler
        if (APPLICATION_ENV == 'development') {
            $profiler = new \Phalcon\Db\Profiler();
            $di->set('profiler', $profiler);

            $eventsManager->attach('db', function ($event, $db) use ($profiler) {
                if ($event->getType() == 'beforeQuery') {
                    $profiler->startProfile($db->getSQLStatement());
                }
                if ($event->getType() == 'afterQuery') {
                    $profiler->stopProfile();
                }
            });
        }

        $db = $di->get('db');
        $db->setEventsManager($eventsManager);

        $dispatcher->setEventsManager($eventsManager);
        $di->set('dispatcher', $dispatcher);
    }

    /**
     * @param \Phalcon\Di\FactoryDefault $di
     */
    private function initView($di)
    {
        $view = new \Phalcon\Mvc\View();

        define('MAIN_VIEW_PATH', '../../../views/');
        $view->setMainView(MAIN_VIEW_PATH . 'main');
        $view->setLayoutsDir(MAIN_VIEW_PATH . '/layouts/');
        $view->setLayout('main');
        $view->setPartialsDir(MAIN_VIEW_PATH . '/partials/');

        // Volt
        $volt = new \Yona\Mvc\View\Engine\Volt($view, $di);
        $volt->setOptions(['compiledPath' => APPLICATION_PATH . '/../data/cache/volt/']);
        $volt->initCompiler();


        $phtml       = new \Phalcon\Mvc\View\Engine\Php($view, $di);
        $viewEngines = [
            ".volt"  => $volt,
            ".phtml" => $phtml,
        ];

        $view->registerEngines($viewEngines);

        $di->set('view', $view);

        return $view;
    }

    /**
     * @param \Phalcon\Di\FactoryDefault $di
     */
    private function initCache($di)
    {
        $config = $di->get('config');

        $cacheFrontend = new \Phalcon\Cache\Frontend\Data([
            "lifetime" => 60,
            "prefix"   => HOST_HASH,
        ]);

        $cache = null;
        switch ($config->cache) {
            case 'file':
                $cache = new \Phalcon\Cache\Backend\File($cacheFrontend, [
                    "cacheDir" => APPLICATION_PATH . "/../data/cache/backend/"
                ]);
                break;
            case 'memcache':
                $cache = new \Phalcon\Cache\Backend\Memcache(
                    $cacheFrontend, [
                    "host" => $config->memcache->host,
                    "port" => $config->memcache->port,
                ]);
                break;
            case 'memcached':
                $cache = new \Phalcon\Cache\Backend\Libmemcached(
                    $cacheFrontend, [
                    "host" => $config->memcached->host,
                    "port" => $config->memcached->port,
                ]);
                break;
        }
        $di->set('cache', $cache, true);
        $di->set('modelsCache', $cache, true);

        \Yona\Widget\Proxy::$cache = $cache; // Modules Widget System

        $modelsMetadata = new \Phalcon\Mvc\Model\Metadata\Memory();
        $di->set('modelsMetadata', $modelsMetadata);

        $di->set('cacheManager', new CacheManager());
    }

    /**
     * @param \Phalcon\Di\FactoryDefault $di
     */
    private function dispatch($di)
    {
        $router = $di['router'];

        $router->handle();

        $view = $di['view'];

        $dispatcher = $di['dispatcher'];

        $response = $di['response'];

        $dispatcher->setModuleName($router->getModuleName());
        $dispatcher->setControllerName($router->getControllerName());
        $dispatcher->setActionName($router->getActionName());
        $dispatcher->setParams($router->getParams());

        $moduleName = \Yona\Utils\ModuleName::camelize($router->getModuleName());

        $ModuleClassName = $moduleName . '\Module';
        if (class_exists($ModuleClassName)) {
            $module = new $ModuleClassName;
            $module->registerAutoloaders();
            $module->registerServices($di);
        }

        $view->start();

        $registry = $di['registry'];
        if ($registry->cms['DEBUG_MODE']) {
            $debug = new \Phalcon\Debug();
            $debug->listen();

            $dispatcher->dispatch();
        } else {
            try {
                $dispatcher->dispatch();
            } catch (\Phalcon\Exception $e) {
                // Errors catching

                $view->setViewsDir(__DIR__ . '/modules/Index/views/');
                $view->setPartialsDir('');
                $view->e = $e;

                if ($e instanceof \Phalcon\Mvc\Dispatcher\Exception) {
                    $response->setHeader(404, 'Not Found');
                    $view->partial('error/error404');
                } else {
                    $response->setHeader(503, 'Service Unavailable');
                    $view->partial('error/error503');
                }
                $response->sendHeaders();
                echo $response->getContent();
                return;

            }
        }

        $view->render(
            $dispatcher->getControllerName(),
            $dispatcher->getActionName(),
            $dispatcher->getParams()
        );

        $view->finish();

        $response = $di['response'];

        $response->setContent($view->getContent());
        $response->sendHeaders();

        echo $response->getContent();
    }

}
