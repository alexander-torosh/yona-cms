<?php
/**
 * @copyright Copyright (c) 2011 - 2014 Aleksandr Torosh (http://wezoom.com.ua)
 * @author Aleksandr Torosh <webtorua@gmail.com>
 */

namespace Cms\Controller;

use Application\Mvc\Controller;
use Cms\Model\Javascript;
use Cms\Form\JavascriptForm;

class JavascriptController extends Controller
{

    public function initialize()
    {
        $this->view->setMainView(MAIN_VIEW_PATH . 'admin');
        $this->helper->activeMenu()->setActive('admin-javascript');

    }

    public function indexAction($id)
    {
        $model = Javascript::findFirst(array("id = '$id'"));
        $form = new JavascriptForm();

        if ($this->request->isPost()) {
            $form->bind($this->request->getPost(), $model);
            if ($form->isValid()) {
                if ($model->save()) {
                    $this->flash->success('Информация обновлена');
                    return $this->redirect('/cms/javascript/index/' . $id);
                } else {
                    $this->flash->error('Информация не обновлена');
                }
            } else {
                $this->flash->error('Информация не обновлена');
            }
        } else {
            $form->setEntity($model);
        }


        ${$id} = 'active'; //init $bottom or $top variable

        $title = 'Редактировать скрипты сайта';
        $this->helper->title($title);
        $this->view->top = $top;
        $this->view->bottom = $bottom;
        $this->view->title = $title;
        $this->view->form = $form;
    }


} 