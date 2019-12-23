<?php
/**
 * @author Alexander Torosh <webtorua@gmail.com>
 */

namespace Api;

use Core\Cache\ApcuCache;
use Core\Config\EnvironmentLoader;
use Phalcon\Db\Adapter\Pdo\Postgresql;
use Phalcon\Di;
use Phalcon\Http\Request;
use Phalcon\Http\Response;
use Phalcon\Mvc\Micro;
use Phalcon\Mvc\Model\Manager as ModelsManager;
use Phalcon\Mvc\Model\Metadata\Memory as ModelsMetadata;
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

        $this->initConfiguration($container);
        $this->initApplicationServices($app);
        $this->initDatabase($app);

        // Initialize API Routing
        $apiRouter = new Router();
        $apiRouter->init($app);

        // Handle exceptions
        $app = $this->handleExceptions($app);

        // Handle request
        $app->handle($_SERVER['REQUEST_URI']);
    }

    private function initConfiguration(Di $container)
    {
        $configLoader = new EnvironmentLoader();
        $configLoader->setDI($container);
        $configLoader->load();
    }

    private function initApplicationServices(Micro $app)
    {
        $app->setService('router', new PhalconRouter(), true);
        $app->setService('request', new Request(), true);
        $app->setService('response', new Response(), true);
    }

    private function initDatabase(Micro $app)
    {
        $database = new Postgresql([
            'host' => getenv('DB_HOST'),
            'port' => getenv('DB_PORT'),
            'username' => getenv('DB_USER'),
            'password' => getenv('DB_PASS'),
            'dbname' => getenv('DB_NAME'),
        ]);
        $app->setService('db', $database, true);
        $app->setService('modelsManager', new ModelsManager());
        $app->setService('modelsMetadata', new ModelsMetadata());
    }

    private function handleExceptions(Micro $app): Micro
    {
        $app->error(
            function ($exception) use ($app) {
                if ('development' === getenv('APP_ENV')) {
                    throw $exception;
                }

                $code = $exception->getCode() ?: 503;

                /** @var \Phalcon\Http\Response $response */
                $response = $app->response;
                $response->setJsonContent([
                    'code' => $code,
                    'status' => 'error',
                    'message' => $exception->getMessage(),
                ]);

                $response->setStatusCode($code);
                $response->send();
            }
        );

        return $app;
    }
}
