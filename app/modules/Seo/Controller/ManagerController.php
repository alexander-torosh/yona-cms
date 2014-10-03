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
        Manager::setTranslateCache(false);
        $this->view->setMainView(MAIN_VIEW_PATH . 'admin');
        $this->helper->activeMenu()->setActive('seo-manager');
    }

    public function indexAction()
    {
        $entries = Manager::find(array(
            'order' => 'route ASC, module ASC, controller ASC, action ASC, id ASC'
        ));
        $this->view->entries = $entries;

        $title = 'SEO-менеджер';
        $this->view->title = $title;
        $this->helper->title($title);
    }

    public function addAction()
    {
        $this->view->pick(array('manager/edit'));
        $model = new Manager();
        $form = new ManagerForm();

        if ($this->request->isPost()) {
            $post = $this->request->getPost();
            $form->bind($post, $model);
            if ($form->isValid()) {
                if ($model->create()) {
                    $form->bind($post, $model);
                    if ($model->update()) {
                        $this->flash->success('Запись создана');
                        $this->redirect('/seo/manager');
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

        $title = 'Создание записи SEO-менеджера';
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
                    $this->flash->success('Информация обновлена');
                    $this->redirect('/seo/manager/edit/' . $id);
                } else {
                    $this->flashErrors($model);
                }
            } else {
                $this->flashErrors($form);
            }
        } else {
            $form->setEntity($model);
        }

        $title = 'Редактирование записи SEO-менеджера';
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
            $this->redirect('/seo/manager');
        }

        $this->view->model = $model;
        $title = 'Удаление записи SEO-менеджера';
        $this->view->title = $title;
        $this->helper->title($title);
    }

} 