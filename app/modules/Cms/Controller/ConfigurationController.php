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
        $this->setAdminEnvironment();
        $this->helper->activeMenu()->setActive('admin-cms');
        $this->view->languages_disabled = true;

    }

    public function indexAction()
    {
        $model = new Configuration();

        $form = new ConfigurationForm();

        if ($this->request->isPost()) {
            $post = $this->request->getPost();
            if ($form->isValid($post)) {
                if ($this->saveFormData($post)) {
                    $this->flash->success($this->helper->at('Configuration saved'));
                    $this->redirect($this->url->get() . 'cms/configuration');
                }
            } else {
                $this->flashErrors($form);
            }
        } else {
            $form->setEntity($model->buildFormData());
        }

        $this->view->form = $form;
        $this->helper->title($this->helper->at('CMS Configuration'), true);
    }

    public function saveFormData($post)
    {
        $result = true;
        foreach (Configuration::$keys as $key => $value) {
            $model = Configuration::findFirst("key = '$key'");
            if (!$model) {
                $model = new Configuration();
                $model->setKey($key);
            }
            if (array_key_exists($key, $post)) {
                $model->setValue($post[$key]);
            } else {
                $model->setValue($value);
            }
            $model->updateCheckboxes($post);
            if (!$model->save()) {
                $result = false;
                $this->flashErrors($model);
            }
        }
        return $result;
    }

} 