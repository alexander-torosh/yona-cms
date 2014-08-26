<?php

namespace Seo\Controller;

use Application\Mvc\Controller;
use Seo\Form\RobotsForm;

class RobotsController extends Controller
{

    private $robotsFilePath;

    public function initialize()
    {
        $this->view->setMainView(MAIN_VIEW_PATH . 'admin');
        $this->helper->activeMenu()->setActive('seo-admin-robots');
        $this->robotsFilePath = PUBLIC_PATH . '/robots.txt';
    }

    public function indexAction()
    {
        $form = new RobotsForm();

        if ($this->request->isPost()) {
            if ($form->isValid()) {
                $robots = $this->request->getPost('robots', 'string');
                $result = file_put_contents($this->robotsFilePath, $robots);
                if ($result) {
                    $this->flash->success('Файл robots.txt обновлен');
                    $this->redirect('/seo/robots');
                } else {
                    $this->flash->error('Ошибка! Файл robots.txt не обновлен. Проверьте права на запись файла.');
                }
            } else {
                $this->flashErrors($form);
            }

            /*$file = $this->request->getPost('file');
            $w_file = file_put_contents($this->robotsFilePath, $file);
            if ($w_file !== false){
                $this->flash->success('Файл robots.txt обновлен');
                $this->redirect('/seo/robots');
            } else {
                $this->flash->error('Ошибка! Файл robots.txt не обновлен');
                $this->view->file = $file;
            }*/

        } else {
            $robots = file_get_contents($this->robotsFilePath);
            $form->get('robots')->setDefault($robots);
            //$r_file = file_get_contents($this->robotsFilePath);
            /*if ($r_file !== false){
                $this->view->file = $r_file;
            } else {
                $this->flash->error('Файл robots.txt ещё не создан или к нему нет доступа');
                $this->view->file = '';

            }*/
        }

        $title = 'Редактирование robots.txt';
        $this->helper->title($title);
        $this->view->title = $title;
        $this->view->form = $form;


    }

}
