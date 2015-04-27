<?php

namespace Page\Controller;

use Application\Mvc\Controller;
use Page\Model\Page;
use Phalcon\Mvc\Dispatcher\Exception;

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

        $this->helper->title()->append($page->getMeta_title());
        $this->helper->meta()->set('description', $page->getMeta_description());
        $this->helper->meta()->set('keywords', $page->getMeta_keywords());

        $this->view->page = $page;

    }

} 