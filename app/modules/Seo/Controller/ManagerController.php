<?php
 /**
 * @copyright Copyright (c) 2011 - 2014 Oleksandr Torosh (http://wezoom.net)
 * @author Oleksandr Torosh <web@wezoom.net>
 */

namespace Seo\Controller;

use Application\Mvc\Controller;
use Seo\Form\ManagerForm;
use Seo\Model\Manager;

class ManagerController extends Controller
{

    public function initialize()
    {
        $this->view->setMainView(MAIN_VIEW_PATH . 'admin');
        $this->helper->activeMenu()->setActive('seo-manager');
    }

    public function indexAction()
    {

    }

    public function addAction()
    {
        $this->view->pick(array('manager/edit'));
        $model = new Manager();
        $form = new ManagerForm();

        if ($this->request->isPost()) {
            $form->bind($this->request->getPost(), $model);
            if ($form->isValid()) {
                if ($model->save()) {
                    $this->flash->success('Запись создана');
                    $this->redirect('/seo/manager');
                } else {
                    $this->flashErrors($model);
                }
            } else {
                $this->flashErrors($form);
            }
        }

        $title = 'Создание записи SEO-менеджера';
        $this->view->title = $title;
        $this->helper->title($title);

        $this->view->model = $model;
        $this->view->form = $form;

    }

    public function editAction($id)
    {

    }

    public function deleteAction($id)
    {

    }

} 