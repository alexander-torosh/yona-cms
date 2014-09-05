<?php

namespace Slider\Controller;

use Application\Mvc\Controller;
use Slider\Model\Slider;
use Slider\Model\SliderImage as Image;
use Slider\Form\SliderForm;

class AdminController extends Controller
{

    private $key = 'slider-inner-';

    private $images = array(
        // images
        'png' => 'image/png',
        'jpe' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'jpg' => 'image/jpeg',
        'gif' => 'image/gif',
    );

    public function initialize()
    {
        $this->view->setMainView(MAIN_VIEW_PATH . 'admin');
        $this->helper->activeMenu()->setActive('admin-slider');
    }

    public function indexAction()
    {
        $this->view->entries = Slider::find();
        $this->view->title = 'Список слайдеров';
    }

    public function addAction()
    {
        $model = new Slider();
        $form = new SliderForm();

        if ($this->request->isPost()) {
            $form->bind($_POST, $model);
            if ($form->isValid()) {
                if (!$model->save()) {
                    $this->echoMessages($model->getMessages());
                } else {
                    $result = $this->uploadImages($model->getId(), 'slider'); //  изображения объявления
                    if ($this->echoMessages($result)) {
//                        if ($model->getImages()) {
//                            $model->setLogoId($model->getImages()->getFirst()->getId());
//                            $model->save();
//                        }
                        $this->redirect('/slider/admin/edit/' . $model->getId());
                    } else {
                        $this->flash->error('Ошибка загрузки изображний');
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

        $this->view->pick('admin/edit');
        $this->view->setVars(array(
            'form' => $form,
            'title' => 'Добавление фотогалереи'
        ));
    }

    public function editAction($id)
    {
        $id = $this->filter->sanitize($id, "int");
        $model = Slider::findFirst($id);
        if (!$model) {
            $this->redirect('/slider/admin/add');
        }

        $form = new SliderForm();
        if ($this->request->isPost()) {
            $form->bind($_POST, $model);
            if ($form->isValid()) {
                $result = $this->uploadImages($model->getId(), 'slider'); //  изображения объявления
                if ($this->echoMessages($result)) {
                    if ($model->save()) {
                        $this->redirect('/slider/admin/edit/' . $model->getId());
                    } else {
                        $this->echoMessages($model->getMessages());
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

        $this->view->setVars(array(
            'form' => $form,
            'model' => $model,
            'title' => 'Редактирование фотогалереи'
        ));
    }

    public function deleteAction($id)
    {
        $id = $this->filter->sanitize($id, "int");
        $entity = Slider::findFirst($id);
        if (!$entity) {
            $this->response->redirect('admin/admin-user');
            return $this->response->send();
        }

        if ($this->request->isPost()) {
            $entity->delete();
            //$this->cache->delete($this->cache->delete($this->key . $entity->getSlug()));
            $this->redirect('/slider/admin/index');
            return $this->response->send();
        }

        $this->view->model = $entity;
        $this->view->title = $this->helper->translate('Удалить галерею');
        $this->helper->title()->append($this->view->title);
    }

    public function deleteImageAction()
    {
        $id = $this->request->getPost('id', 'int');
        $this->view->cleanTemplateBefore();

        $model = Image::findFirst(array('id = ' . $id));

        if ($model) {
            $imageFilter = new \Image\Filter(array(
                'id' => $id,
                'type' => 'slider'
            ));
            $imageFilter->remove(true);

            $entity = Slider::findFirst('id = ' . $model->getSliderId());

//            if ($entity->getLogoId() == $id) {
//                $result = 'preview-delete';
//                $entity->setLogoId($entity->getImages(array('order' => 'sort ASC'))->getFirst()->getId());
//                $entity->update();
//            }
            if ($model->delete()) {
                if ($result != 'preview-delete') {
                    $result = true;
                }
            } else {
                $result = false;
            }
        } else {
            $result = false;
        }

        $this->response->setHeader('Content-Type', 'text/plain');
        $this->response->setContentType('application/json', 'UTF-8');
        $this->response->setStatusCode(200, 'OK');

        $this->response->setContent(json_encode($result));
        $this->view->disable();
        echo !!$result;
        return $this->response;
    }

    public function saveSliderAction()
    {
        $slider_id = (int)$this->request->getPost('slider', 'int');

        $this->view->cleanTemplateBefore();

        $itemsData = $this->request->getPost('items');
//        $logo_id = (int)$this->request->getPost('logo', 'int');
//
//        if ($logo_id) {
//            $model = Slider::findFirst('id = ' . $slider_id);
//            if ($model) {
//                $model->setLogoId($logo_id);
//                $model->update();
//            }
//        }

        foreach ($itemsData as $k => $v) {
            $imageModel = Image::findFirst('id = ' . $k . ' AND slider_id = ' . $slider_id);
            $imageModel->setSortOrder($v['sort']);
            $imageModel->setCaption($v['text']);
            $imageModel->update();
        }

        $this->response->setHeader('Content-Type', 'text/plain');
        $this->response->setContentType('application/json', 'UTF-8');
        $this->response->setStatusCode(200, 'OK');

        $this->response->setContent(json_encode($_POST));
        $this->view->disable();
        echo '1'; return;
        return $this->response;
    }

    public function uploadImages($id, $type)
    {
        if ($this->request->hasFiles() == true) {
            $files = $this->request->getUploadedFiles();
            $messages = $this->validateImages($files, $id);

            if (empty($messages)) {
                foreach ($files as $key => $file) {
                    if ($file->getKey() != 'logo') {
                        $image = new \Slider\Model\SliderImage();
                        $image->setSliderId($id);
                        $image->save();
                        $filename = $key . '.jpg';
                        $fullFilePath = $file->getTempName();
                        //$file->moveTo($fullFilePath);

                        $imageFilter = new \Image\Filter(array(
                            'id' => $image->getId(),
                            'type' => $type
                        ));
                        $imageFilter->remove(false);
                        $originalAbsPath = $imageFilter->originalAbsPath();
                        if (is_file($fullFilePath)) {
                            copy($fullFilePath, $originalAbsPath);
                            unlink($fullFilePath);
                        }
                    }
                }
            }
            return $messages;
        }
    }

    private function validateImages($files)
    {
        $massages = array();

        foreach ($files as $k) {
            if (!in_array($k->getType(), $this->images)) {
                $massages[] = 'Файл ' . $k->getName() . ' не являеться изображением';
            }
        }
        return $massages;
    }

    private function echoMessages($messages)
    {
        if (!empty($messages)) {
            foreach ($messages as $message) {
                $this->flash->error($message);
            }
            return false;
        } else {
            return true;
        }
    }
}