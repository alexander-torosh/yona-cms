<?php
/**
 * Created by PhpStorm.
 * User: office-pb1
 * Date: 07.07.14
 * Time: 22:46
 */

namespace Projects\Controller;

use Application\Mvc\Controller;
use Projects\Form\ProjectForm;
use Projects\Model\Project;
use Projects\Model\ProjectImage;

class AdminController extends Controller
{

    public function initialize()
    {
        $this->setAdminEnvironment();
        $this->helper->activeMenu()->setActive('admin-projects');

        $this->view->entries = Project::find(array(
            'order' => 'sortorder DESC'
        ));

    }

    public function indexAction()
    {
        $this->view->title = 'Список проектов';
    }

    public function addAction()
    {
        $this->view->pick(array('admin/edit'));
        $form = new ProjectForm();
        $model = new Project();

        if ($this->request->isPost()) {
            $form->bind($this->request->getPost(), $model);
            if ($form->isValid()) {
                if ($model->save()) {
                    $this->uploadImage($model);
                    $this->flash->success('Проект создан');
                    return $this->redirect('/projects/admin/edit/' . $model->getId());
                } else {
                    $this->flashErrors($model);
                }
            } else {
                $this->flashErrors($form);
            }
        }

        $this->view->model = $model;
        $this->view->form = $form;
        $this->view->title = 'Создание проекта';

    }

    public function editAction($id)
    {
        $id = (int)$id;
        $form = new ProjectForm();
        $model = Project::findFirst($id);

        if ($this->request->isPost()) {
            $form->bind($this->request->getPost(), $model);
            if ($form->isValid()) {
                if ($model->save()) {
                    $this->uploadImage($model);
                    $this->flash->success('Информация обновлена');
                    return $this->redirect('/projects/admin/edit/' . $model->getId());
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
        $this->view->title = 'Редактирование проекта';
    }

    public function deleteAction($id)
    {

    }

    private function uploadImage($model)
    {
        if ($this->request->isPost()) {
            if ($this->request->hasFiles() == true) {
                foreach ($this->request->getUploadedFiles() as $file) {
                    if (!in_array($file->getType(), array(
                        'image/bmp',
                        'image/jpeg',
                        'image/png',
                    ))
                    ) {
                        return $this->flash->error('Разрешается загружать только файлы с расширением jpg, jpeg, png, bmp');
                    }

                    $projectImage = new ProjectImage();
                    $projectImage->setProjectId($model->getId());
                    $projectImage->create();

                    $imageFilter = new \Image\Storage(array(
                        'id' => $projectImage->getId(),
                        'type' => 'project',
                    ));

                    $resize_x = 1000;
                    $resize_y = 1000;

                    $successMsg = 'Фото добавлено';

                    $imageFilter->removeCached();

                    require_once __DIR__ . '/../../Image/vendor/PHPThumb/ThumbLib.inc.php';
                    $thumb = \PhpThumbFactory::create($file->getTempName());
                    $thumb->resize($resize_x, $resize_y);
                    $thumb->save($imageFilter->originalAbsPath());

                    $this->flash->success($successMsg);
                }
            }
        }

    }


} 