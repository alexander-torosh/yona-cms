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
        $this->view->setMainView(MAIN_VIEW_PATH . 'admin');
        $this->helper->activeMenu()->setActive('admin-page');

    }

    public function indexAction()
    {
        $this->view->entries = Page::find();

        $this->view->title = 'Список страниц';
        $this->helper->title('Список страниц');
    }

    public function addAction()
    {
        $this->view->pick(array('admin/edit'));
        $form = new PageForm();
        $model = new Page();

        if ($this->request->isPost()) {
            $form->bind($this->request->getPost(), $model);
            if ($form->isValid()) {
                if ($model->save()) {
                    $this->flash->success('Страница создана');
                    return $this->redirect('/page/admin/edit/' . $model->getId());
                } else {
                    $this->flashErrors($model);
                }
            } else {
                $this->flashErrors($form);
            }
        }

        $this->view->model = $model;
        $this->view->form = $form;
        $this->view->title = 'Создание страницы';
        $this->helper->title('Создание страницы');

    }

    public function editAction($id)
    {
        $id = (int)$id;
        $form = new PageForm();
        $model = Page::findFirst(array("id = $id"));

        if ($this->request->isPost()) {
            $form->bind($this->request->getPost(), $model);
            if ($form->isValid()) {
                if ($model->save()) {
                    $this->flash->success('Информация обновлена');

                    // Очищаем кеш страницы
                    $query = "slug = '{$model->getSlug()}'";
                    $key = md5("Page::findFirst($query)");
                    $this->cache->delete($key);

                    return $this->redirect('/page/admin/edit/' . $model->getId());
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
        $this->view->title = 'Редактирование страницы';
        $this->helper->title('Редактирование страницы');
    }

    public function deleteAction($id)
    {
        $model = Page::findFirst($id);

        if ($this->request->isPost()) {
            $model->delete();
            $this->redirect('/page/admin');
        }

        $this->view->model = $model;
        $this->view->title = 'Удаление страницы';
        $this->helper->title('Удаление страницы');
    }

} 