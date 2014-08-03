<?php

/**
 * AdminUserController
 * @copyright Copyright (c) 2011 - 2014 Aleksandr Torosh (http://wezoom.com.ua)
 * @author Aleksandr Torosh <webtorua@gmail.com>
 */

namespace Admin\Controller;

use Application\Mvc\Controller;
use Admin\Form\AdminUserForm;
use Admin\Model\AdminUser;

class AdminUserController extends Controller
{

    public function initialize()
    {
        $this->view->setMainView(MAIN_VIEW_PATH . 'admin');
        $this->helper->activeMenu()->setActive('admin-user');

    }

    public function indexAction()
    {
        $this->view->entries = AdminUser::find(array(
                    "order" => "id DESC"
        ));

        $this->view->title = $this->helper->translate('Administrators');
        $this->helper->title()->append($this->view->title);

    }

    public function addAction()
    {
        $this->view->pick(array('admin-user/edit'));

        $form = new AdminUserForm();
        $form->initAdding();

        if ($this->request->isPost()) {
            $model = new AdminUser();
            $form->bind($this->request->getPost(), $model);
            if ($form->isValid()) {
                if ($model->save()) {
                    $this->flash->success($this->helper->translate('Administrator <b>%login%</b> created', array('login' => $model->getLogin())));
                    $this->response->redirect('admin/admin-user');
                    return $this->response->send();
                } else {
                    foreach ($model->getMessages() as $message) {
                        $this->flash->error($message);
                    }
                }
            } else {
                foreach ($form->getMessages() as $message) {
                    $this->flash->error($message);
                }
            }
        }

        $this->view->form = $form;
        $this->view->submitButton = $this->helper->translate('Add');

        $this->view->title = $this->helper->translate('Add Administrator');
        $this->helper->title()->append($this->view->title);

    }

    public function editAction($id)
    {
        $form  = new AdminUserForm();
        $model = AdminUser::findFirst("id = $id");

        if ($this->request->isPost()) {
            $form->bind($this->request->getPost(), $model);
            if ($form->isValid()) {
                if ($model->save() == true) {
                    $this->flash->success($this->helper->translate('Administrator <b>%login%</b> saved', array('login' => $model->getLogin())));
                    $this->response->redirect('admin/admin-user');
                    return $this->response->send();
                } else {
                    foreach ($model->getMessages() as $message) {
                        $this->flash->error($message);
                    }
                }
            } else {
                foreach ($form->getMessages() as $message) {
                    $this->flash->error($message);
                }
            }
        } else {
            $form->setEntity($model->getPopulateData());
        }

        $this->view->form = $form;
        $this->view->submitButton = $this->helper->translate('Save');
        $this->view->model = $model;

        $this->view->title = $this->helper->translate('Edit Administrator');
        $this->helper->title()->append($this->view->title);

    }

    public function deleteAction($id)
    {
        $model = AdminUser::findFirst("id = $id");
        if (!$model) {
            $this->response->redirect('admin/admin-user');
            return $this->response->send();
        }

        if ($this->request->isPost()) {
            $model->delete();
            $this->flash->warning($this->helper->translate('Administrator <b>%login%</b> deleted', array('login' => $model->getLogin())));
            $this->response->redirect('admin/admin-user');
            return $this->response->send();
        }

        $this->view->model = $model;
        $this->view->title = $this->helper->translate('Delete Administrator');
        $this->helper->title()->append($this->view->title);

    }

}
