<?php

namespace Seo\Controller;

use Application\Mvc\Controller;

class RobotsController extends Controller
{

    private $robotsFilePath;

    public function initialize()
    {
        $this->view->setMainView(MAIN_VIEW_PATH . 'admin');
        $this->helper->activeMenu()->setActive('seo-admin-robots');
        $this->robotsFilePath = PUBLIC_PATH . '/robots.txt';
    }

    public function indexAction()
    {

        if (isset($_POST['file'])){
            file_put_contents($this->robotsFilePath, $_POST['file']);
        }

        $this->view->file = file_get_contents($this->robotsFilePath);
    }

}
