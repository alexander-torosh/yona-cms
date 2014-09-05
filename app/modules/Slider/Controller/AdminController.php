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
        $this->view->pick('admin/edit');
        $form = new SliderForm();
        $model = new Slider();


        if ($this->request->isPost()) {
            $form->bind($this->request->getPost(), $model);
            if ($form->isValid()) {
                if ($model->save()) {
                    $this->flash->success('Слайдер создан');
                    $result = $this->uploadImages($model->getId(), 'slider');
                    if (!$this->echoMessages($result)) {
                        $this->flash->error('Ошибка загрузки изображний');
                    }
                    $this->redirect('/slider/admin/edit/' . $model->getId());

                } else {
                    $this->echoMessages($model->getMessages());
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
            'title' => 'Добавление слайдера'
        ));
    }

    public function editAction($id)
    {
        $id = (int)$id;
        $form = new SliderForm();
        $model = Slider::findFirst(array("id = $id"));

        if ($this->request->isPost()) {
            $form->bind($this->request->getPost(), $model);
            if ($form->isValid()) {
                if ($model->save()) {
                    $result = $this->uploadImages($model->getId(), 'slider');
                    $this->flash->success('Информация обновлена');
                    if ($this->echoMessages($result)) {
                        $this->redirect('/slider/admin/edit/' . $model->getId());
                    }
                } else {
                    $this->flash->error('Слайдер не сохранен!');
                    $this->echoMessages($model->getMessages());
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
        $id = (int)$id;
        $model = Slider::findFirst($id);

        if ($this->request->isPost()) {
            $model->delete();
            $this->redirect('/slider/admin');
        }

        $this->view->model = $model;
        $this->view->title = $this->helper->translate('Удалить слайдер');
        $this->helper->title('Удаление публикации');
    }

    public function deleteImageAction()
    {
        $id = (int)$id;
        $this->view->cleanTemplateBefore();

        $model = Image::findFirst(array('id = ' . $id));

        if ($model) {
            $imageFilter = new \Image\Filter(array(
                'id' => $id,
                'type' => 'slider'
            ));
            $imageFilter->remove(true);

            $entity = Slider::findFirst('id = ' . $model->getSliderId());

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
            $messages = $this->validateImages($files);

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