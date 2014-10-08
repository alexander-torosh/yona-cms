<?php
/**
 * Created by PhpStorm.
 * User: office-pb1
 * Date: 18.07.14
 * Time: 20:49
 */

namespace Video\Controller;

use Application\Mvc\Controller;
use Video\Model\Video;
use Video\Form\VideoForm;

class AdminController extends Controller
{

    public function initialize()
    {
        $this->setAdminEnvironment();
        $this->helper->activeMenu()->setActive('admin-video');

    }

    public function indexAction()
    {
        $this->view->entries = Video::find(array(
            'order' => 'sortorder ASC'
        ));

        $this->view->title = 'Список видео';
    }

    public function addAction()
    {
        $this->view->pick(array('admin/edit'));
        $form = new VideoForm();
        $model = new Video();

        if ($this->request->isPost()) {
            $form->bind($this->request->getPost(), $model);
            if ($form->isValid()) {
                if ($model->save()) {
                    $this->flash->success('Видео создано');
                    return $this->redirect('/video/admin/edit/' . $model->getId());
                } else {
                    $this->flashErrors($model);
                }
            } else {
                $this->flashErrors($form);
            }
        }

        $this->view->model = $model;
        $this->view->form = $form;
        $this->view->title = 'Создание видео';

    }

    public function editAction($id)
    {
        $id = (int)$id;
        $form = new VideoForm();
        $model = Video::findFirst($id);

        if ($this->request->isPost()) {
            $form->bind($this->request->getPost(), $model);
            if ($form->isValid()) {
                if ($model->save()) {
                    $this->flash->success('Информация обновлена');
                    return $this->redirect('/video/admin/edit/' . $model->getId());
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
        $this->view->title = 'Редактирование видео';
    }

    public function deleteAction($id)
    {
        $model = Video::findFirst($id);

        if ($this->request->isPost()) {
            $model->delete();
            $this->redirect('/video/admin');
        }

        $this->view->model = $model;
    }

} 