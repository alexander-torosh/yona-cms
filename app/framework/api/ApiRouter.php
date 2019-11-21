<?php
/**
 * @author Alexander Torosh <webtorua@gmail.com>
 */

namespace Api;

use Api\Controllers\IndexController;
use Api\Exception\NotFoundException;
use Phalcon\Mvc\Micro;
use Phalcon\Mvc\Micro\Collection as MicroCollection;

class ApiRouter
{
    /**
     * @param Micro $app
     */
    public function init(Micro $app)
    {
        // Not Found
        $app = $this->handleNotFound($app);

        // Mount Routes
        $app->mount($this->index());
    }

    /**
     * @param Micro $app
     * @return Micro
     */
    private function handleNotFound(Micro $app): Micro
    {
        // Not Found
        $app->notFound(
            function () use ($app) {
                throw new NotFoundException('Page Not Found', 404);
            }
        );

        return $app;
    }

    /**
     * @return MicroCollection
     */
    private function index(): MicroCollection
    {
        $collection = new MicroCollection();
        $collection->setHandler(IndexController::class, true);
        $collection->setPrefix('/api');

        $collection->get('/', 'index');
        $collection->get('/test', 'test');

        return $collection;
    }
}