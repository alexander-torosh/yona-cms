<?php
 /**
 * @copyright Copyright (c) 2011 - 2014 Oleksandr Torosh (http://wezoom.net)
 * @author Oleksandr Torosh <web@wezoom.net>
 */

namespace Publication\Controller;

use Application\Mvc\Controller;
use Publication\Form\TypeForm;
use Publication\Model\Type;

class TypeController extends Controller
{

    public function initialize()
    {
        $this->setAdminEnvironment();
    }

    public function indexAction()
    {
        $this->view->entries = Type::find();
        $this->view->title = 'Типы публикаций';
        $this->helper->title($this->view->title);
    }

    public function addAction()
    {
        $form = new TypeForm();
        $model = new Type();



        $this->view->model = $model;
        $this->view->form = $form;

        $this->view->title = 'Добавление типа публикаций';
        $this->helper->title($this->view->title);
    }

    public function editAction()
    {
        $this->view->title = 'Редактирование типа публикаций';
        $this->helper->title($this->view->title);
    }

    public function deleteAction()
    {
        $this->view->title = 'Удаление типа публикаций';
        $this->helper->title($this->view->title);
    }

} 