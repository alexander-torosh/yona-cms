<?php

namespace FileManager\Controller;

use Application\Mvc\Controller;

class IndexController extends Controller
{

    public function initialize()
    {
        $this->view->setMainView(MAIN_VIEW_PATH . 'admin');
        $this->helper->activeMenu()->setActive('admin-fm');

    }

    public function indexAction()
    {
        $this->helper->title()->append('Файловый менеджер');

    }

}
