<?php
/**
 * @copyright Copyright (c) 2011 - 2014 Aleksandr Torosh (http://wezoom.net)
 * @author Aleksandr Torosh <webtorua@gmail.com>
 */

namespace Cms\Controller;

use Application\Mvc\Controller;
use Cms\Form\LanguageForm;
use Cms\Model\Language;

class LanguageController extends Controller
{

    public function initialize()
    {
        $this->setAdminEnvironment();
        $this->helper->activeMenu()->setActive('admin-language');
        $this->view->languages_disabled = true;

    }

    public function indexAction()
    {
        $this->view->entries = Language::find(array(
            'order' => 'primary DESC, sortorder ASC',
        ));

        $this->view->title = 'Список языков';
        $this->helper->title('Список языков');
    }

    public function addAction()
    {
        $this->view->pick(array('language/edit'));
        $form = new LanguageForm();
        $model = new Language();

        if ($this->request->isPost()) {
            $form->bind($this->request->getPost(), $model);
            if ($form->isValid()) {
                if ($model->save()) {
                    $this->cache->delete(Language::cacheKey());
                    $this->flash->success('Информация обновлена');
                    return $this->redirect('/cms/language');
                } else {
                    $this->flashErrors($model);
                }
            } else {
                $this->flashErrors($form);
            }
        }

        $this->view->model = $model;
        $this->view->form = $form;

        $this->view->title = 'Добавление языка';
        $this->helper->title($this->view->title);
    }

    public function editAction($id)
    {
        $form = new LanguageForm();
        $model = Language::findFirst($id);

        if ($this->request->isPost()) {
            $form->bind($this->request->getPost(), $model);
            if ($form->isValid()) {
                ($this->request->getPost('primary') != null) ? $model->setPrimary(1) : $model->setPrimary(0);
                if ($model->save()) {
                    $this->flash->success('Информация обновлена');
                    $this->cache->delete(Language::cacheKey());
                    return $this->redirect('/cms/language/edit/' . $model->getId());
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

        $this->view->title = 'Редактирование языка';
        $this->helper->title($this->view->title);
    }

    public function deleteAction($id)
    {

    }

} 