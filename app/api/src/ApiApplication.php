<?php
/**
 * @author Alexander Torosh <webtorua@gmail.com>
 */

namespace Api;

use josegonzalez\Dotenv\Loader as EnvLoader;
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Micro;

class ApiApplication
{
    public function run()
    {
        // Dependency Injector
        $di = new FactoryDefault();

        // Env configuration
        (new EnvLoader(__DIR__ . '/../../../.env'))
            ->parse()
            ->putenv();

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
                $response = $app->response;
                $response->setJsonContent([
                    'code'    => $exception->getCode(),
                    'status'  => 'error',
                    'message' => $exception->getMessage(),
                ]);

                $response->setStatusCode($exception->getCode());
                $response->send();
            }
        );

        return $app;
    }
}