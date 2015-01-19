<?php
/**
 * Created by PhpStorm.
 * User: office-pb1
 * Date: 18.07.14
 * Time: 20:49
 */

namespace Page\Controller;

use Application\Mvc\Controller;
use Page\Model\Page;
use Page\Form\PageForm;

class AdminController extends Controller
{

    public function initialize()
    {
        $this->setAdminEnvironment();
        $this->helper->activeMenu()->setActive('admin-page');
        Page::setTranslateCache(false);

    }

    public function indexAction()
    {
        $this->view->entries = Page::find();

        $this->view->title = $this->helper->at('Manage Pages');
        $this->helper->title($this->view->title);
    }

    public function addAction()
    {
        $this->view->pick(array('admin/edit'));
        $form = new PageForm();
        $model = new Page();

        if ($this->request->isPost()) {
            $post = $this->request->getPost();
            $form->bind($post, $model);
            if ($form->isValid()) {
                if ($model->create()) {
                    $form->bind($post, $model);
                    $model->updateFields($post);
                    if ($model->update()) {
                        $this->flash->success($this->helper->at('Page created'));
                        return $this->redirect('/page/admin/edit/' . $model->getId() . '?lang=' . LANG);
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

        $helper = $this->di->get('helper');
        $this->view->title = $helper->at('Manage Pages');
        $this->helper->title($this->view->title);

        $this->view->model = $model;
        $this->view->form = $form;


    }

    public function editAction($id)
    {
        $id = (int)$id;
        $form = new PageForm();
        $model = Page::findFirst($id);

        if ($model->getSlug() == 'index') {
            $form->get('slug')->setAttribute('disabled','disabled');
        }

        if ($this->request->isPost()) {
            $post = $this->request->getPost();
            $form->bind($post, $model);
            if ($form->isValid()) {
                $model->updateFields($post);
                if ($model->save()) {
                    $this->flash->success($this->helper->at('Updated has been successful'));

                    // Очищаем кеш страницы
                    $query = "slug = '{$model->getSlug()}'";
                    $key = md5("Page::findFirst($query)");
                    $this->cache->delete($key);

                    return $this->redirect('/page/admin/edit/' . $model->getId() . '?lang=' . LANG);
                } else {
                    $this->flashErrors($model);
                }
            } else {
                $this->flashErrors($form);
            }
        } else {
            $form->setEntity($model);
        }

        $this->view->model = $model;
        $this->view->form = $form;
        $this->view->title = $this->helper->at('Edit Page');
        $this->helper->title($this->view->title);
    }

    public function deleteAction($id)
    {
        $model = Page::findFirst($id);

        if ($model->getSlug() == 'index') {
            die($this->helper->at('Index page can not be removed'));
        }

        if ($this->request->isPost()) {
            $model->delete();
            $this->redirect('/page/admin');
        }

        $this->view->model = $model;
        $this->view->title = $this->helper->at('Delete Page');
        $this->helper->title($this->view->title);
    }

} 