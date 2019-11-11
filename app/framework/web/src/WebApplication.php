<?php
/**
 * @author Alexander Torosh <webtorua@gmail.com>
 */

namespace Web;

use Core\Config\EnvironmentLoader;
use Phalcon\Debug;
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Application;
use Phalcon\Mvc\Dispatcher;

class WebApplication
{
    public function run()
    {
        // Dependency Injector
        $di = new FactoryDefault();

        // Env configuration
        $env = getenv('APP_ENV');
        $configLoader = new EnvironmentLoader();
        $configLoader->load(__DIR__ .'/../../../../.env', $env !== 'development');

        if (getenv('APP_ENV') === 'development') {
            $debug = new Debug();
            $debug->listen();
        }

        // Initialize app
        $app = new Application();

        // Bind DI to the app
        $app->setDI($di);

        // Create and bind an EventsManager Manager to the app
        $events = new WebEventsManager();
        $eventsManager = $events->getEventsManager();
        $di->set('eventsManager', $eventsManager);

        // View
        $di->set('view', new View());

        // Dispatcher
        $dispatcher = new Dispatcher();
        $dispatcher->setDefaultNamespace('Web\Controllers');
        $dispatcher->setEventsManager($eventsManager);
        $di->set('dispatcher', $dispatcher);

        try {
            // Handle the request
            $response = $app->handle();
            $response->send();

        } catch (Dispatcher\Exception $e) {
            // 404 Not Found
            $this->notFoundError($app);

        } catch (\Exception $e) {
            // 503 Server Error
            $this->serviceUnavailableError($app, $e);
        }
    }

    /**
     * @param Application $app
     */
    private function notFoundError(Application $app)
    {
        $app->view->render('errors', '404-not-found');
        $app->response
            ->setStatusCode(404)
            ->send();
    }

    /**
     * @param Application $app
     * @param \Exception $e
     */
    private function serviceUnavailableError(Application $app, \Exception $e)
    {
        $app->view->render('errors', '503-service-unavailable', [
            'message' => $e->getMessage(),
        ]);
        $app->response
            ->setStatusCode(503)
            ->send();
    }
}