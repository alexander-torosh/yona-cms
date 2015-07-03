<?php

namespace Seo\Controller;

use Application\Mvc\Controller;
use Seo\Form\SitemapForm;

class SitemapController extends Controller
{

    private $sitemapFilePath;

    public function initialize()
    {
        $this->setAdminEnvironment();
        $this->helper->activeMenu()->setActive('seo-sitemap');
        $this->sitemapFilePath = ROOT . '/sitemap.xml';
        $this->view->languages_disabled = true;
    }

    public function indexAction()
    {
        $form = new SitemapForm();

        if ($this->request->isPost()) {
            if ($form->isValid()) {
                $sitemap = $this->request->getPost('sitemap');
                $result = file_put_contents($this->sitemapFilePath, $sitemap);
                if ($result) {
                    $this->flash->success('File sitemap.xml has been saved');
                    $this->redirect($this->url->get() . 'seo/sitemap');
                } else {
                    $this->flash->error('Error! The sitemap.xml file is not updated. Check the write permissions to the file.');
                }
            } else {
                $this->flashErrors($form);
            }
        } else {
            $sitemap = file_get_contents($this->sitemapFilePath);
            $form->get('sitemap')->setDefault($sitemap);
        }

        $title = 'Editing sitemap.xml';
        $this->helper->title($title);
        $this->view->title = $title;
        $this->view->form = $form;
    }

}
