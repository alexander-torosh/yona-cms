<?php

namespace Api;

use Api\Application\Middleware\JwtMiddleware;
use Api\Application\Plugin\ErrorHandler;
use Api\Application\Service\JwtService;
use Phalcon\Translate\Adapter\NativeArray;

class MicroKernel extends \Phalcon\Mvc\Micro
{
    public function run()
    {
        // Service loader
        $configServices = include BASE_PATH . '/config/services.php';
        $di = new \Phalcon\DI\FactoryDefault();
        $serviceLoader = new \Core\Service\Loader($configServices, $di);
        $di->set('serviceLoader', $serviceLoader, true);

        $appConfig = $di->get('appConfig');

        define('IMAGES_SERVER', $appConfig->get('images_server'));
        define('STATIC_SERVER', $appConfig->get('static_server'));


        $this->setDI($di);

        // mount collections


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

        // Middleware after the route is executed
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

            }
        );

        // handle app
        $this->handle();
    }
}