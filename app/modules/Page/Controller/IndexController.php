<?php

namespace Page\Controller;

use Application\Mvc\Controller;
use Page\Model\Helper\PageHelper;
use Page\Model\Page;
use Phalcon\Mvc\Dispatcher\Exception;

class IndexController extends Controller
{

    public function indexAction()
    {
        $slug = $this->dispatcher->getParam('slug', 'string');
        
        $pageHelper = new PageHelper();
        $pageResult = $pageHelper->pageBySlug($slug);
        if (!$pageResult) {
            throw new Exception("Page '$slug.html' not found");
        }

        $this->helper->title()->append($pageResult->meta_title);
        $this->helper->meta()->set('description', $pageResult->meta_description);
        $this->helper->meta()->set('keywords', $pageResult->meta_keywords);

        $this->view->text = $pageResult->text;
    }

    public function contactsAction()
    {
        $pageHelper = new PageHelper();
        $pageResult = $pageHelper->pageBySlug('contacts');
        if (!$pageResult) {
            throw new Exception("Page 'contacts' not found");
        }

        $this->helper->title()->append($pageResult->meta_title);
        $this->helper->meta()->set('description', $pageResult->meta_description);
        $this->helper->meta()->set('keywords', $pageResult->meta_keywords);

        $this->helper->menu->setActive('contacts');

        $this->view->text = $pageResult->text;
    }

}
