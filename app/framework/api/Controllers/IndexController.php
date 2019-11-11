<?php
/**
 * @author Alexander Torosh <webtorua@gmail.com>
 */

namespace Api\Controllers;

use Api\ApiController;
use Domain\User\Entity\User;

class IndexController extends ApiController
{
    public function index()
    {
        // $this->getDI()->get('eventsManager')->fire('test:event', $this, ['test' => 1]);

        $this->json([
            'success' => true,
            'env' => getenv('APP_ENV'),
        ]);
    }
}