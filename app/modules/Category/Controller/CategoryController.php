<?php

/**
 * CategoryController
 * @copyright Copyright (c) 2011 - 2014 Aleksandr Torosh (http://wezoom.com.ua)
 * @author Aleksandr Torosh <webtorua@gmail.com>
 */

namespace Category\Controller;

use Application\Mvc\Controller;
use Category\Model\Category;
use Category\Form\CategoryForm;

class CategoryController extends Controller
{

    public function initialize()
    {
        $this->view->setMainView(MAIN_VIEW_PATH . 'admin');
        $this->helper->activeMenu()->setActive('admin-category');

    }

    public function indexAction()
    {
        $model             = new Category();
        $this->view->model = $model;

        $this->view->title = $this->helper->translate('Перечень категорий');
        $this->helper->title()->append($this->view->title);

    }

    public function addAction()
    {
        $this->view->pick(array('category/edit'));

        $form  = new CategoryForm();
        $model = new Category();

        if ($this->request->isPost()) {
            $form->bind($this->request->getPost(), $model);
            if ($form->isValid()) {
                if ($model->save()) {
                    //$this->cache->delete(md5('Category('.$model->getId().')->getChildren()'));
                    $this->flash->success("Категория создана");

                    return $this->response->redirect('category/category/edit/' . $model->getId());
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

        $this->view->form  = $form;
        $this->view->model = $model;

        $this->view->title = $this->helper->translate('Добавление категории');
        $this->helper->title()->append($this->view->title);

    }

    public function editAction($id)
    {
        $form  = new CategoryForm();
        $model = Category::findFirst("id = $id");
        if (!$model) {
            return $this->response->redirect('category/category');
        }

        if ($this->request->isPost()) {
            $form->bind($this->request->getPost(), $model);
            if ($form->isValid()) {
                if ($model->save()) {
                    $this->uploadPreview($model);
                    $this->flash->success("Категория сохранена");

                    return $this->response->redirect('category/category/edit/' . $model->getId());
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
            $form->setEntity($model);
        }

        $this->view->form           = $form;
        $this->view->model          = $model;
        $this->view->categoriesTree = $model->getCategoriesTreeByType($model->getType(), null, false, $model->getId());

        $this->view->title = $this->helper->translate('Редактирование категории');
        $this->helper->title()->append($this->view->title);

    }

    public function deleteAction($id)
    {
        $model = Category::findFirst("id = $id");
        if (!$model) {
            return $this->response->redirect('category/category');
        }

        if ($this->request->isPost() && $this->request->getPost('delete')) {
            $model->delete();
            $this->flash->success("Категория удалена");

            return $this->response->redirect('category/category');
        }

        $this->view->model = $model;
        $this->view->title = $this->helper->translate('Удаление категории');
        $this->helper->title()->append($this->view->title);


    }

    private function uploadPreview($model)
    {
        if ($this->request->isPost()) {
            if ($this->request->hasFiles() == true) {
                foreach ($this->request->getUploadedFiles() as $file) {
                    if (!in_array($file->getType(), array(
                        'image/bmp',
                        'image/jpg',
                        'image/jpeg',
                        'image/png',
                    ))
                    ) {
                        return $this->flash->error('Разрешается загружать только файлы с расширением jpg, jpeg, png, bmp');
                    }

                    $imageFilter = new \Image\Filter(array(
                        'id'   => $model->getId(),
                        'type' => 'category',
                    ));
                    $imageFilter->removeCached();

                    require_once __DIR__ . '/../../Image/vendor/PHPThumb/ThumbLib.inc.php';
                    $thumb = \PhpThumbFactory::create($file->getTempName());
                    $thumb->resize(172, 200);
                    $thumb->save($imageFilter->originalAbsPath());

                    $this->flash->success('Превью сохранено');
                }
            }
        }

    }

}
