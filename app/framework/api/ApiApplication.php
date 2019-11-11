<?php
/**
 * @author Alexander Torosh <webtorua@gmail.com>
 */

namespace Api;

use Core\Config\EnvironmentLoader;
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Micro;

class ApiApplication
{
    public function run()
    {
        // Dependency Injector
        $di = new FactoryDefault();

        // Env configuration
        $env = getenv('APP_ENV');
        $configLoader = new EnvironmentLoader();
        $configLoader->load(__DIR__ .'/../../../.env', $env !== 'development');

        // Initialize micro app
        $app = new Micro();

        // Bind DI to the app
        $app->setDI($di);

        // Create and bind an EventsManager Manager to the app
        $events = new ApiEventsManager();
        $di->set('eventsManager', $events->getEventsManager());

        // Router
        $router = new Router();
        $app = $router->init($app);

        // Handle exceptions
        $app = $this->handleExceptions($app);

        // Handle
        $app->handle();
    }

    private function handleExceptions(Micro $app): Micro
    {
        $app->error(
            function ($exception) use ($app) {
                $code = $exception->getCode() ?: 503;

                $response = $app->response;
                $response->setJsonContent([
                    'code'    => $code,
                    'status'  => 'error',
                    'message' => $exception->getMessage(),
                ]);

                $response->setStatusCode($code);
                $response->send();
            }
        );

        return $app;
    }
}