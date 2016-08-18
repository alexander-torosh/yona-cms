<?php

namespace Seo\Controller;

use Application\Mvc\Controller;
use Seo\Form\RobotsForm;

class RobotsController extends Controller
{

    private $robotsFilePath;

    public function initialize()
    {
        $this->setAdminEnvironment();
        $this->helper->activeMenu()->setActive('seo-robots');
        $this->robotsFilePath = ROOT . '/robots.txt';
        $this->view->languages_disabled = true;
    }

    public function indexAction()
    {
        $form = new RobotsForm();

        if ($this->request->isPost()) {
            if ($form->isValid()) {
                $robots = $this->request->getPost('robots', 'string');
                $result = file_put_contents($this->robotsFilePath, $robots);
                if ($result) {
                    $this->flash->success('File robots.txt has been saved');
                    $this->redirect($this->url->get() . 'seo/robots');
                } else {
                    $this->flash->error('Error! The robots.txt file is not updated. Check the write permissions to the file.');
                }
            } else {
                $this->flashErrors($form);
            }
        } else {
            $robots = file_get_contents($this->robotsFilePath);
            $form->get('robots')->setDefault($robots);
        }

        $this->helper->title('Editing robots.txt', true);
        $this->view->form = $form;


    }

}
