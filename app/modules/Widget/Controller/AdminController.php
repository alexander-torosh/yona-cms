<?php

namespace Widget\Controller;

use Application\Mvc\Controller;
use Widget\Model\Widget;
use Widget\Form\WidgetForm;

class AdminController extends Controller
{

    public function initialize()
    {
        $this->setAdminEnvironment();
        $this->helper->activeMenu()->setActive('admin-widget');

        $this->view->languages_disabled = true;

    }

    public function indexAction()
    {
        $this->view->entries = Widget::find();

        $this->helper->title($this->helper->at('Manage Widgets'), true);

    }

    public function addAction()
    {
        $widget = new Widget();
        $form = new WidgetForm();

        if ($this->request->isPost()) {
            $form->bind($this->request->getPost(), $widget);
            if ($form->isValid()) {
                if ($widget->save()) {
                    $this->redirect($this->url->get() . 'widget/admin/edit/' . $widget->getId());
                } else {
                    $this->flashErrors($widget);
                }
            } else {
                $this->flashErrors($form);
            }
        } else {
            $form->setEntity($widget);
        }

        $this->view->pick('admin/edit');
        $this->view->setVar('form', $form);

        $this->view->title = $this->helper->at('Adding widget');
        $this->helper->title($this->view->title);

    }

    public function editAction($id)
    {
        $id = $this->filter->sanitize($id, "string");
        $widget = Widget::findFirst(["id = '$id'"]);
        if (!$widget) {
            $this->redirect($this->url->get() . 'widget/admin/add');
        }

        $form = new WidgetForm();
        $form->remove('id');
        if ($this->request->isPost()) {

            $form->bind($this->request->getPost(), $widget);
            if ($form->isValid()) {
                if ($widget->save()) {
                    $this->redirect($this->url->get() . 'widget/admin/edit/' . $widget->getId());
                } else {
                    $this->flashErrors($widget);
                }
            } else {
                $this->flashErrors($form);
            }
        } else {
            $form->setEntity($widget);
        }

        $this->view->setVar('form', $form);
        $this->view->setVar('widget', $widget);

        $this->helper->title($this->helper->at('Editing widget'), true);

    }

    public function deleteAction($id)
    {
        $id = $this->filter->sanitize($id, "string");
        $model = Widget::findFirst(["id = '$id'"]);
        if ($model) {

            if ($this->request->isPost()) {
                $model->delete();
                $this->redirect($this->url->get() . 'widget/admin/index');
            }

            $this->view->model = $model;
        }

    }

}