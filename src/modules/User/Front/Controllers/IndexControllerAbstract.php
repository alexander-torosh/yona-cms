<?php

namespace User\Front\Controllers;

use Core\ControllerAbstract;

class IndexControllerAbstract extends ControllerAbstract
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
