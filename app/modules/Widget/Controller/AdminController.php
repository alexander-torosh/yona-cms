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

    }

    public function indexAction()
    {
        $this->view->setVar('entries', Widget::find());

        $this->view->title = $this->helper->at('Manage Widgets');
        $this->helper->title($this->view->title);

    }

    public function addAction()
    {
        $widget = new Widget();
        $form = new WidgetForm();

        if ($this->request->isPost()) {
            $form->bind($_POST, $widget);
            if ($form->isValid()) {
                if ($widget->save()) {
                    $this->redirect('/widget/admin/edit/' . $widget->getId());
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
        $id   = $this->filter->sanitize($id, "string");
        $widget = Widget::findFirst(array("id = '$id'"));
        if (!$widget) {
            $this->redirect('/widget/admin/add');
        }

        $form = new WidgetForm();
        $form->remove('id');
        if ($this->request->isPost()) {

            $form->bind($_POST, $widget);
            if ($form->isValid()) {
                if ($widget->save()) {
                    $this->redirect('/widget/admin/edit/' . $widget->getId());
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

        $this->view->title = $this->helper->at('Editing widget');
        $this->helper->title($this->view->title);

    }

    public function deleteAction($id)
    {
        $id   = $this->filter->sanitize($id, "string");
        $widget = Widget::findFirst(array("id = '$id'"));
        if ($widget) {

            if ($this->request->isPost()) {
                $widget->delete();
                $this->redirect('/widget/admin/index');
            }

            $this->view->setVar('widget', $widget);
        }

    }

}