<?php

namespace Slider\Controller;

use Application\Mvc\Controller;
use Slider\Model\Slider;
use Slider\Model\SliderImage as Image;
use Slider\Form\SliderForm;

class AdminController extends Controller
{

    private $key = 'slider-inner-'; // use in template - {{ helper.getSlider(slider ID) }}

    private $allowedFormats = array(
        'png' => 'image/png',
        'jpe' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'jpg' => 'image/jpeg',
        'gif' => 'image/gif',
    );

    public function initialize()
    {
        $this->setAdminEnvironment();
        $this->helper->activeMenu()->setActive('admin-slider');
        $this->view->languages_disabled = true;

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
                    $this->uploadImages($model->getId());
                    return $this->redirect('/slider/admin/edit/' . $model->getId());

                } else {
                    $this->flashErrors($model);
                }
            } else {
                $this->flashErrors($form);
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
                $this->uploadImages($model->getId());
                if ($model->save()) {
                    $this->flash->success('Информация обновлена');
                    return $this->redirect('/slider/admin/edit/' . $model->getId());
                } else {
                    $this->flash->error('Информация не сохранена!');
                    $this->flashErrors($model);
                }

            } else {
                $this->flashErrors($form);
            }
        } else {
            $form->setEntity($model);
        }

        $this->view->setVars(array(
            'form' => $form,
            'model' => $model,
            'title' => 'Редактирование слайдера'
        ));
    }

    public function deleteAction($id)
    {
        $id = (int)$id;
        $model = Slider::findFirst($id);

        if ($this->request->isPost()) {
            foreach ($model->SliderImages as $img)
            {
                $imageFilter = new \Image\Storage(array(
                    'id' => $img->getId(),
                    'type' => 'slider'
                ));
                $imageFilter->remove(true);
            }
            $model->delete();
            return $this->redirect('/slider/admin');
        }

        $this->view->model = $model;
        $this->helper->title('Удаление слайдера', true);
    }

    public function deleteImageAction()
    {
        $id = $this->request->getPost('id', 'int');
        $this->view->cleanTemplateBefore();
        $result = false;

        $model = Image::findFirst(array('id = ' . $id));

        if ($model) {
            $imageFilter = new \Image\Storage(array(
                'id' => $id,
                'type' => 'slider'
            ));
            $imageFilter->remove(true);

            if ($model->delete()) {
                $result = true;
            }
        }

        $this->returnJSON(array('success' => $result));
    }

    public function saveSliderAction()
    {
        $slider_id = $this->request->getPost('slider', 'int');
        $itemsData = $this->request->getPost('items');

        if (count($itemsData)){
            foreach ($itemsData as $k => $v) {
                $imageModel = Image::findFirst('id = ' . $k . ' AND slider_id = ' . $slider_id);
                $imageModel->setSortOrder($v['sort']);
                $imageModel->setCaption($v['text']);
                $imageModel->setLink($v['link']);
                $imageModel->update();
            }
        }

        $this->returnJSON(array('success' => 'true'));
    }

    public function uploadImages($id)
    {
        if ($this->request->isPost()) {
            if ($this->request->hasFiles() == true) {
                foreach ($this->request->getUploadedFiles() as $file) {

                    if (in_array($file->getRealType(), $this->allowedFormats)) {
                        $image = new \Slider\Model\SliderImage();
                        $image->setSliderId($id);
                        $image->save();

                        $imageFilter = new \Image\Storage(array(
                            'id' => $image->getId(),
                            'type' => 'slider',
                        ));
                        $imageFilter->remove(false);
                        $file->moveTo($imageFilter->originalAbsPath());
                    } else {
                        $this->flash->error('Разрешается загружать только картинки с расширением jpg, jpeg, png, gif! '. $file->getName() .' - не загружен.' );
                    }
                }

            }
        }
    }

}