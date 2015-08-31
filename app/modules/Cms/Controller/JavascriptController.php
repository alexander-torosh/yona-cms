<?php
/**
 * @copyright Copyright (c) 2011 - 2014 Aleksandr Torosh (http://wezoom.com.ua)
 * @author Aleksandr Torosh <webtorua@gmail.com>
 */

namespace Cms\Controller;

use Application\Mvc\Controller;
use Cms\Model\Javascript;
use Cms\Form\JavascriptForm;

class JavascriptController extends Controller
{

    public function initialize()
    {
        $this->setAdminEnvironment();
        $this->helper->activeMenu()->setActive('admin-javascript');
        $this->view->languages_disabled = true;

    }

    public function indexAction()
    {
        $head = Javascript::findFirst("id = 'head'");
        $body = Javascript::findFirst("id = 'body'");

        $form = new JavascriptForm();

        if ($this->request->isPost()) {
            if ($form->isValid($this->request->getPost())) {
                $head->setText($this->request->getPost('head'));
                $body->setText($this->request->getPost('body'));
                if ($head->save() && $body->save()) {
                    $this->flash->success($this->helper->at('Updated has been successful'));
                    return $this->redirect($this->url->get() . 'cms/javascript');
                } else {
                    $this->flash->error($this->helper->at('Errors saving'));
                }
            } else {
                $this->flashErrors($form);
            }
        } else {
            $form->get('head')->setDefault($head->getText());
            $form->get('body')->setDefault($body->getText());
        }

        $title = htmlentities('<head>, <body> javascript');
        $this->helper->title($title);
        $this->view->title = $title;

        $this->view->form = $form;
    }


} 