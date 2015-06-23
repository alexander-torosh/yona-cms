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
        Manager::setTranslateCache(false);
        $this->setAdminEnvironment();
        $this->helper->activeMenu()->setActive('seo-manager');
        $this->view->languages_disabled = true;
    }

    public function indexAction()
    {
        $entries = Manager::find([
            'order' => 'route ASC, module ASC, controller ASC, action ASC, id ASC'
        ]);
        $this->view->entries = $entries;

        $title = 'SEO-Manager';
        $this->view->title = $title;
        $this->helper->title($title);
    }

    public function addAction()
    {
        $model = new Manager();
        $form = new ManagerAddForm();

        if ($this->request->isPost()) {
            $post = $this->request->getPost();
            $form->bind($post, $model);
            if ($form->isValid()) {
                if ($model->create()) {
                    $form->bind($post, $model);
                    if ($model->update()) {
                        $this->flash->success('This entry was posted');
                        $this->redirect($this->url->get().'seo/manager');
                    } else {
                        $this->flashErrors($model);
                    }
                } else {
                    $this->flashErrors($model);
                }
            } else {
                $this->flashErrors($form);
            }
        }

        $title = 'Create a record SEO-Manager';
        $this->view->title = $title;
        $this->helper->title($title);

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
                    $this->cache->delete(Manager::routeCacheKey($model->getRoute(), LANG));
                    $this->flash->success('SEO record edited');
                    $this->redirect($this->url->get().'seo/manager/edit/'.$id);
                } else {
                    $this->flashErrors($model);
                }
            } else {
                $this->flashErrors($form);
            }
        } else {
            $form->setEntity($model);
        }

        $title = 'Editing the SEO-manager';
        $this->view->title = $title;
        $this->helper->title($title);

        $this->view->model = $model;
        $this->view->form = $form;
    }

    public function deleteAction($id)
    {
        $model = Manager::findFirst($id);

        if ($this->request->isPost()) {
            $model->delete();
            $this->redirect($this->url->get().'seo/manager');
        }

        $this->view->model = $model;
        $title = 'Delete SEO-Manager';
        $this->view->title = $title;
        $this->helper->title($title);
    }

} 