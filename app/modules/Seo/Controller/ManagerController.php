<?php
/**
 * @copyright Copyright (c) 2011 - 2014 Oleksandr Torosh (http://wezoom.net)
 * @author Oleksandr Torosh <web@wezoom.net>
 */

namespace Seo\Controller;

use Application\Mvc\Controller;
use Seo\Form\ManagerAddForm;
use Seo\Form\ManagerForm;
use Seo\Model\Manager;

class ManagerController extends Controller
{

    public function initialize()
    {
        $this->setAdminEnvironment();
        $this->helper->activeMenu()->setActive('seo-manager');
        $this->view->languages_disabled = true;
    }

    public function indexAction()
    {
        $this->view->entries = Manager::find([
            'order' => 'id DESC'
        ]);

        $this->helper->title('SEO-Manager', true);
    }

    public function addAction()
    {
        $this->view->pick(['manager/edit']);
        $model = new Manager();
        $form = new ManagerForm();

        if ($this->request->isPost()) {
            $post = $this->request->getPost();
            $form->bind($post, $model);
            if ($form->isValid()) {
                if ($model->save()) {
                    $this->flash->success('This entry was posted');
                    $this->redirect($this->url->get() . 'seo/manager');
                } else {
                    $this->flashErrors($model);
                }
            } else {
                $this->flashErrors($form);
            }
        }

        $this->helper->title('Create SEO-Manager record', true);

        $this->view->model = $model;
        $this->view->form = $form;
    }

    public function editAction($id)
    {
        $model = Manager::findFirst($id);
        $form = new ManagerForm();

        if ($this->request->isPost()) {
            $form->bind($this->request->getPost(), $model);
            if ($form->isValid()) {
                if ($model->save()) {
                    $this->flash->success('SEO record edited');
                    $this->redirect($this->url->get() . 'seo/manager/edit/' . $id);
                } else {
                    $this->flashErrors($model);
                }
            } else {
                $this->flashErrors($form);
            }
        } else {
            $form->setEntity($model);
        }

        $this->helper->title('Editing SEO-manager record', true);

        $this->view->model = $model;
        $this->view->form = $form;
    }

    public function deleteAction($id)
    {
        $model = Manager::findFirst($id);

        if ($this->request->isPost()) {
            $model->delete();
            $this->redirect($this->url->get() . 'seo/manager');
        }

        $this->view->model = $model;
        $this->helper->title('Deleting SEO-Manager record', true);
    }

} 