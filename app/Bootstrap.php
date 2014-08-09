<?php

/**
 * Bootstrap
 * @copyright Copyright (c) 2011 - 2014 Aleksandr Torosh (http://wezoom.com.ua)
 * @author Aleksandr Torosh <webtorua@gmail.com>
 */
use \Phalcon\Mvc\View;

class Bootstrap
{

    public static function run()
    {
        if (in_array(APPLICATION_ENV, array('development')) || $_GET['debug'] == 'true') {
            $debug = new \Phalcon\Debug();
            $debug->listen();
        }


        $di = new \Phalcon\DI\FactoryDefault();


        $config = include APPLICATION_PATH . '/config/application.php';
        $di->set('config', $config);


        $registry = new \Phalcon\Registry();


        $loader = new \Phalcon\Loader();
        $loader->registerNamespaces($config->loader->namespaces->toArray());
        $loader->register();
        $loader->registerDirs(array(APPLICATION_PATH . "/plugins/"));


        $db = new \Phalcon\Db\Adapter\Pdo\Mysql(array(
            "host" => $config->database->host,
            "username" => $config->database->username,
            "password" => $config->database->password,
            "dbname" => $config->database->dbname,
            "charset" => $config->database->charset,
        ));
        $di->set('db', $db);


        $view = new View();

        define('MAIN_VIEW_PATH', '../../../views/');
        $view->setMainView(MAIN_VIEW_PATH . 'main');
        $view->setLayoutsDir(MAIN_VIEW_PATH . '/layouts/');
        $view->setPartialsDir(MAIN_VIEW_PATH . '/partials/');

        $volt = new \Phalcon\Mvc\View\Engine\Volt($view, $di);
        $volt->setOptions(array('compiledPath' => APPLICATION_PATH . '/cache/volt/'));

        $phtml = new \Phalcon\Mvc\View\Engine\Php($view, $di);
        $viewEngines = array(
            ".volt" => $volt,
            ".phtml" => $phtml,
        );
        $registry->viewEngines = $viewEngines;

        $view->registerEngines($viewEngines);

        if (isset($_GET['_ajax']) && $_GET['_ajax']) {
            $view->setRenderLevel(View::LEVEL_LAYOUT);
        }

        $di->set('view', $view);


        $viewSimple = new \Phalcon\Mvc\View\Simple();
        $viewSimple->registerEngines($viewEngines);
        $di->set('viewSimple', $viewSimple);


        $url = new \Phalcon\Mvc\Url();
        $url->setBasePath('/');
        $url->setBaseUri('/');


        $cache = new \Phalcon\Cache\Backend\Memcache(
            new \Phalcon\Cache\Frontend\Data(array(
                "lifetime" => 60,
                "prefix" => 'schuco_landing',
            )), array(
            "host" => "localhost",
            "port" => "11211"
        ));
        $di->set('cache', $cache);
        $di->set('modelsCache', $cache);


        if (APPLICATION_ENV == 'development') {
            $modelsMetadata = new \Phalcon\Mvc\Model\Metadata\Memory();
        } else {
            $modelsMetadata = new \Phalcon\Mvc\Model\MetaData\Apc(array(
                "lifetime" => 60,
                "prefix" => 'schuco_landing',
            ));
        }
        $di->set('modelsMetadata', $modelsMetadata);


        $application = new \Phalcon\Mvc\Application();

        $application->registerModules($config->modules->toArray());


        $router = new \Application\Mvc\Router\DefaultRouter();
        foreach ($application->getModules() as $module) {
            $className = str_replace('Module', 'Routes', $module['className']);
            if (class_exists($className)) {
                $class = new $className();
                $router = $class->init($router);
            }
        }
        $di->set('router', $router);

        $eventsManager = new \Phalcon\Events\Manager();
        $dispatcher = new \Phalcon\Mvc\Dispatcher();

        $eventsManager->attach("dispatch:beforeException", function ($event, $dispatcher, $exception) {
            new ExceptionPlugin($dispatcher, $exception);
        });

        $eventsManager->attach("dispatch:beforeDispatchLoop", function ($event, $dispatcher, $di) use ($di) {
            new LocalizationPlugin($dispatcher);
            new AclPlugin($di->get('acl'), $dispatcher);
        });

        // <Profiler>
        $profiler = new Phalcon\Db\Profiler();

        $eventsManager->attach('db', function ($event, $db) use ($profiler) {
            if ($event->getType() == 'beforeQuery') {
                $profiler->startProfile($db->getSQLStatement());
            }
            if ($event->getType() == 'afterQuery') {
                $profiler->stopProfile();
            }
        });

        $db->setEventsManager($eventsManager);
        $di->set('profiler', $profiler);
        // </Profiler>


        $dispatcher->setEventsManager($eventsManager);
        $di->set('dispatcher', $dispatcher);

        $session = new \Phalcon\Session\Adapter\Files();
        $session->start();
        $di->set('session', $session);

        $acl = new \Application\Acl\DefaultAcl();
        $di->set('acl', $acl);

        $assets = new \Phalcon\Assets\Manager();
        $di->set('assets', $assets);

        $flash = new \Phalcon\Flash\Session(array(
            'error' => 'ui red inverted segment',
            'success' => 'ui green inverted segment',
            'notice' => 'ui blue inverted segment',
            'warning' => 'ui orange inverted segment',
        ));
        $di->set('flash', $flash);

        $di->set('helper', new \Application\Mvc\Helper());

        $di->set('registry', $registry);

        $assetsManager = new \Phalcon\Assets\Manager();
        $di->set('assets', $assetsManager);


        $application->setDI($di);

        if (isset($_GET['_ajax']) && $_GET['_ajax']) {
            return self::getAjaxContent($application);
        } else {
            self::echoContent($application);
        }

    }

    private static function getAjaxContent($application)
    {
        ob_start();
        self::echoContent($application);
        $contents = ob_get_contents();
        ob_end_clean();

        $return = new \stdClass();
        $return->html = $contents;
        $di = $application->getDi();
        $return->title = $di->get('helper')->title()->get();
        $return->success = true;

        $view = $di->get('view');
        if ($view->bodyClass) {
            $return->bodyClass = $view->bodyClass;
        }

        $response = $di->get('response');
        $headers = $response->getHeaders()->toArray();
        if (isset($headers[404]) || isset($headers[503])) {
            $return->success = false;
        }

        $response->setContentType('application/json', 'UTF-8');
        $response->setContent(json_encode($return));
        return $response->send();
    }

    private static function echoContent($application)
    {
        try {
            echo $application->handle()->getContent();
        } catch (\Phalcon\Exception $e) {
            if (in_array(APPLICATION_ENV, array('development')) || $_GET['debug'] == 'true') {
                echo "<pre>";
                echo $e->getMessage() . PHP_EOL;
                print_r($e->getTrace());
                exit;
            } else {
                $application->getDi()->get('helper')->error(404);
            }
        }
    }

}
