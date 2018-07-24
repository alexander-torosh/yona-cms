<?php

namespace User\Front\Controllers;

use Core\BaseController;

class IndexController extends BaseController
{
    public function index()
    {
        // starts rendering process enabling the output buffering
        $this->view->start();

        // rendering
        $this->view->render(
            'index',
            'index',
            [
                'message' => __METHOD__,
            ]
        );

        // finishes the render process by stopping the output buffering
        $this->view->finish();

        // return content
        return $this->view->getContent();
    }
}
