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
        $this->setAdminEnvironment();
        $this->helper->activeMenu()->setActive('admin-user');
        $this->view->languages_disabled = true;

    }

    public function indexAction()
    {
        $this->view->entries = AdminUser::find(array(
                    "order" => "id DESC"
        ));

        $this->view->title = $this->helper->at('Manage Users');
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
                    $this->flash->success($this->helper->at('User created'), ['name' => $model->getLogin()]);
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
        $this->view->submitButton = $this->helper->at('Add New');

        $this->view->title = $this->helper->at('Administrator');
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
                    $this->flash->success('Администратор <b>'.$model->getLogin().'</b> сохранен');
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
        $this->view->submitButton = $this->helper->at('Save');
        $this->view->model = $model;

        $this->view->title = $this->helper->at('Manage Users');
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
            $this->flash->warning('Администратор <b>'.$model->getLogin().'</b> удален');
            $this->response->redirect('admin/admin-user');
            return $this->response->send();
        }

        $this->view->model = $model;
        $this->view->title = $this->helper->at('Delete User');
        $this->helper->title()->append($this->view->title);

    }

}
