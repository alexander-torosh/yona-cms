<?php

namespace Page\Controller;

use Application\Mvc\Controller;
use Page\Model\Page;
use Phalcon\Mvc\Dispatcher\Exception;

class IndexController extends Controller
{

    public function indexAction()
    {
        $slug = $this->dispatcher->getParam('slug', 'string');
        $page = Page::findCachedBySlug($slug);
        if (!$page) {
            throw new Exception("Page '$slug.html' not found");
        }

        $this->helper->title()->append($page->getMetaTitle());
        $this->helper->meta()->set('description', $page->getMetaDescription());
        $this->helper->meta()->set('keywords', $page->getMetaKeywords());

        $this->view->page = $page;
    }

    public function contactsAction()
    {
        $page = Page::findCachedBySlug('contacts');
        if (!$page) {
            throw new Exception("Page 'contacts' not found");
        }

        $this->helper->title()->append($page->getMeta_title());
        $this->helper->meta()->set('description', $page->getMetaDescription());
        $this->helper->meta()->set('keywords', $page->getMetaKeywords());
        $this->view->page = $page;

        $this->helper->menu->setActive('contacts');
    }

}
