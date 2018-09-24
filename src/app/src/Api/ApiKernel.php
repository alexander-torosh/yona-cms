<?php

namespace Application\Api;

use Application\Api\Plugin\ErrorHandler;
use Core\MicroAbstract;
use Core\Middleware\MicroModuleMiddleware;
use Core\Service\LoaderService;
use Core\MicroModules;
use Phalcon\Config;
use Phalcon\Events\Manager as EventsManager;
use Phalcon\DI\FactoryDefault;

class ApiKernel extends MicroAbstract
{
    public function getPrefix(): string
    {
        return 'api';
    }

    public function init(array $listModules, array $config): void
    {
        $di = new FactoryDefault();

        // config
        $di->setShared('appConfig', function () use ($config) {
            return new Config($config);
        });

        // services loader
        $configServices = include CONFIG_PATH . 'services.php';
        $serviceLoader = new LoaderService($configServices, $di);
        $di->set('serviceLoader', $serviceLoader, true);

        // init modules service
        $modules = new MicroModules();
        $modules->init($this, $listModules);
        $di->setShared('modules', $modules);

        // attach middleware
        /** @var EventsManager $eventsManager */
        $eventsManager = $di->get('eventsManager');
        // initialize routes and modules
        $eventsManager->attach('micro', new MicroModuleMiddleware());

        // make events manager is in the DI container now
        $this->setEventsManager($eventsManager);

        $this->setDI($di);
    }

    public function run(): void
    {
        // if not found
        $this->notFound(
            function () {
                $this->response->setJsonContent([
                    'error' => 'Nothing to see here.',
                ]);
                $this->response->setStatusCode(404);
                $this->response->send();
            }
        );

        // middleware after the route is executed
        $this->after(
            function () {
                $response = $this->getReturnedValue();

                if ($response !== null) {
                    if (is_string($response)) {
                        $this->response->setContent($response);
                    } else {
                        $this->response->setJsonContent($response);
                    }

                    $this->response->setStatusCode(200);
                    $this->response->send();
                }
            }
        );

        // error handler
        $this->error(
            function (\Exception $e) {
                $handler = new ErrorHandler();
                return $handler->handle($e);
            }
        );

        // handle app
        $this->handle();
    }
}
