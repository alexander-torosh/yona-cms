<?php
/**
 * @copyright Copyright (c) 2011 - 2014 Aleksandr Torosh (http://wezoom.com.ua)
 * @author Aleksandr Torosh <webtorua@gmail.com>
 */

namespace Cms\Controller;

use Application\Mvc\Controller;
use Cms\Form\ConfigurationForm;
use Cms\Model\Configuration;

class ConfigurationController extends Controller
{

    public function initialize()
    {
        $this->view->setMainView(MAIN_VIEW_PATH . 'admin');
        $this->helper->activeMenu()->setActive('admin-cms');

    }

    public function indexAction()
    {
        $model = new Configuration();

        $form = new ConfigurationForm();

        if ($this->request->isPost()) {
            $post = $this->request->getPost();
            if ($form->isValid($post)) {
                if ($this->saveFormData($post)) {
                    $this->flash->success('Настройки сохранены');
                    $this->redirect('/cms/configuration');
                }
            } else {
                $this->flashErrors($form);
            }
        } else {
            $form->setEntity($model->buildFormData());
        }

        $this->view->form = $form;

        $title = 'Конфигурация CMS';
        $this->view->title = $title;
        $this->helper->title($title);
    }

    public function saveFormData($post)
    {
        $result = true;
        foreach ($post as $key => $value) {
            if ($key == 'form') {
                continue;
            }
            $model = Configuration::findFirst("key = '$key'");
            if (!$model) {
                $model = new Configuration();
                $model->setKey($key);
            }
            $model->setValue($value);
            if (!$model->save()) {
                $result = false;
                $this->flashErrors($model);
            }
        }
        return $result;
    }

} 