<?php
/**
 * @author Alexander Torosh <webtorua@gmail.com>
 */

namespace Api;

use Core\Cache\ApcuCache;
use Core\Config\EnvironmentLoader;
use Phalcon\Di;
use Phalcon\Http\Request;
use Phalcon\Http\Response;
use Phalcon\Mvc\Micro;
use Phalcon\Mvc\Router as PhalconRouter;

class Application
{
    public function run()
    {
        // DI Container
        $container = new Di();

        // Initialize micro app
        $app = new Micro($container);

        // Set serverCache service
        $app->setService('serverCache', (new ApcuCache())->init(), true);

        // Env configuration
        $configLoader = new EnvironmentLoader();
        $configLoader->setDI($container);
        $configLoader->load();

        // Define default services
        $app->setService('router', new PhalconRouter(), true);
        $app->setService('request', new Request(), true);
        $app->setService('response', new Response(), true);


        // Initialize API Routing
        $apiRouter = new Router();
        $apiRouter->init($app);

        // Handle exceptions
        $app = $this->handleExceptions($app);

        // Handle request
        $app->handle($_SERVER["REQUEST_URI"]);
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