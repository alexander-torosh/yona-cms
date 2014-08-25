<?php

namespace Page\Controller;

use Application\Mvc\Controller;
use Page\Model\Page;
use Phalcon\Exception;

class IndexController extends Controller
{

    public function indexAction()
    {
        $slug = $this->dispatcher->getParam('slug','string');
        $page = Page::findCachedBySlug($slug);
        if (!$page) {
            throw new Exception("Page '$slug.html' not found");
            return;
        }

        $this->helper->title()->append($page->getMetaTitle());
        $this->helper->meta()->set('description', $page->getMetaDescription());
        $this->helper->meta()->set('keywords', $page->getMetaKeywords());

        $this->view->page = $page;

    }

} 