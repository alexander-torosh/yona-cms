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
                    $this->uploadImages($model->getId(), 'slider');
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
                $this->uploadImages($model->getId(), 'slider');
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
                $imageFilter = new \Image\Filter(array(
                    'id' => $img->getId(),
                    'type' => 'slider'
                ));
                $imageFilter->remove(true);
            }
            $model->delete();
            return $this->redirect('/slider/admin');
        }

        $this->view->model = $model;
        $this->view->title = 'Удалить слайдер';
        $this->helper->title('Удаление публикации');
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
        $slider_id = $this->request->getPost('slider', 'int');

        $this->view->cleanTemplateBefore();

        $itemsData = $this->request->getPost('items');

        foreach ($itemsData as $k => $v) {
            $imageModel = Image::findFirst('id = ' . $k . ' AND slider_id = ' . $slider_id);
            $imageModel->setSortOrder($v['sort']);
            $imageModel->setCaption($v['text']);
            $imageModel->setLink($v['link']);
            $imageModel->update();

            $query = 'foreign_id = ' . $k . ' AND lang = "' . LANG . '"'; //for \Application\Mvc\Model->getTranslations();
            $key = HOST_HASH . md5('slider_image_translate ' . $query);
            $this->cache->delete($key);
        }

        $this->response->setHeader('Content-Type', 'text/plain');
        $this->response->setContentType('application/json', 'UTF-8');
        $this->response->setStatusCode(200, 'OK');

        $this->response->setContent(json_encode($this->request->getPost()));
        $this->view->disable();
        echo '1'; return;
        return $this->response;
    }

    public function uploadImages($id, $type)
    {
        if ($this->request->isPost()) {
            if ($this->request->hasFiles() == true) {
                foreach ($this->request->getUploadedFiles() as $key => $file) {

                    if (in_array($file->getType(), $this->allowedFormats)) {
                        $image = new \Slider\Model\SliderImage();
                        $image->setSliderId($id);
                        $image->setLink($v['link']);
                        $image->save();
                        $filename = $key . '.jpg';
                        $fullFilePath = $file->getTempName();
                        //$file->moveTo($fullFilePath);

                        $imageFilter = new \Image\Filter(array(
                            'id' => $image->getId(),
                            'type' => $type,
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