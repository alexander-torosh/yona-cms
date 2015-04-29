<?php

namespace Publication\Controller;

use Application\Mvc\Controller;
use Publication\Model\Publication;
use Publication\Form\PublicationForm;
use Publication\Model\Type;

class AdminController extends Controller
{

    public function initialize()
    {
        $this->setAdminEnvironment();
        $this->helper->activeMenu()->setActive('admin-publication');

    }

    public function indexAction()
    {
        $page = $this->request->getQuery('page', 'int', 1);
        $type = $this->dispatcher->getParam('type');

        $types = Type::find();

        if ($type) {
            $typeEntity = Type::getCachedBySlug($type);
            $type_id = $typeEntity->getId();
        }

        $cond_array = array();
        if ($type) {
            $cond_array[] = "type_id = $type_id";
        }
        $conditions = implode(' AND ', $cond_array);

        $publications = Publication::find(array(
            "conditions" => $conditions,
            "order" => "date DESC, id DESC"
        ));

        $paginator = new \Phalcon\Paginator\Adapter\Model(array(
            "data" => $publications,
            "limit" => 20,
            "page" => $page
        ));
        $this->view->paginate = $paginator->getPaginate();

        $this->view->types = $types;
        $this->view->type = $type;
        $this->view->type_id = $type_id;

        $this->view->title = $this->helper->at('Manage Publications');
        $this->helper->title($this->view->title);
    }

    public function addAction()
    {
        $this->view->pick(array('admin/edit'));
        $form = new PublicationForm();
        $model = new Publication();

        $type = $this->dispatcher->getParam('type');
        if ($type) {
            $typeEntity = Type::getCachedBySlug($type);
            $form->get('type_id')->setDefault($typeEntity->getId());
            $this->view->type = $type;
        }

        if ($this->request->isPost()) {
            $post = $this->request->getPost();
            $form->bind($post, $model);

            if ($form->isValid()) {
                if ($model->create()) {
                    $form->bind($post, $model);
                    $model->updateFields($post);
                    if ($model->update()) {
                        $this->flash->success($this->helper->at('Publication created'));
                        return $this->redirect('/publication/admin/edit/' . $model->getId() . '?lang=' . LANG);
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

        $this->view->model = $model;
        $this->view->form = $form;

        $this->view->title = $this->helper->at('Create a publication');
        $this->helper->title($this->view->title);

    }

    public function editAction($id)
    {
        $id = (int)$id;
        $form = new PublicationForm();
        $model = Publication::findFirst($id);

        if ($model->getType_id()) {
            $this->view->type = $model->getType()->getSlug();
        }

        if ($this->request->isPost()) {
            $post = $this->request->getPost();
            $form->bind($post, $model);
            if ($form->isValid()) {
                $model->updateFields($post);
                if ($model->save()) {
                    $this->uploadImage($model);
                    $this->flash->success($this->helper->at('Publication edited'));

                    return $this->redirect('/publication/admin/edit/' . $model->getId() . '?lang=' . LANG);
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
        $this->view->title = $this->helper->at('Edit publication');
        $this->helper->title($this->view->title);
    }

    public function deleteAction($id)
    {
        $model = Publication::findFirst($id);

        if ($this->request->isPost()) {
            $model->delete();
            if ($model->getType_id()) {
                $this->redirect('/publication/admin/' . $model->getType()->getSlug());
            } else {
                $this->redirect('/publication/admin');
            }
        }

        $this->view->model = $model;
        $this->view->title = $this->helper->at('Unpublishing');
        $this->helper->title($this->view->title);
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
                        return $this->flash->error($this->helper->at('Only allow image formats jpg, jpeg, png, bmp'));
                    }

                    $imageFilter = new \Image\Storage(array(
                        'id' => $model->getId(),
                        'type' => 'publication',
                    ));

                    $resize_x = 1000;

                    $successMsg = $this->helper->at('Photo added');

                    $imageFilter->removeCached();

                    $image = new \Phalcon\Image\Adapter\GD($file->getTempName());
                    if ($image->getWidth() > $resize_x) {
                        $image->resize($resize_x);
                    }
                    $image->save($imageFilter->originalAbsPath());

                    $this->flash->success($successMsg);
                }
            }
        }
    }

} 