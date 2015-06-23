<?php

namespace FileManager\Controller;

use Application\Mvc\Controller;

class IndexController extends Controller
{

    public function initialize()
    {
        $this->setAdminEnvironment();
        $this->helper->activeMenu()->setActive('admin-fm');
        $this->view->languages_disabled = true;
    }

    public function indexAction()
    {
        $this->helper->title()->append('File Manager');

    }

}
