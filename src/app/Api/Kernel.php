<?php

namespace Application\Api;

use Application\Api\Plugin\ErrorHandler;
use Application\MicroKernel;
use Core\Service\CollectionsLoaderService;

class Kernel extends MicroKernel
{
    public function getPrefix(): string
    {
        return 'api';
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

        // mount collections
        $collectionsLoader = new CollectionsLoaderService();
        $collectionsLoader->include($this);

        // handle app
        $this->handle();
    }
}