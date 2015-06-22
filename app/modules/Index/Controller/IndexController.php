<?php

namespace Index\Controller;

use Application\Mvc\Controller;
use Page\Model\Page;
use Phalcon\Exception;

class IndexController extends Controller
{

    public function indexAction()
    {
        $this->view->bodyClass = 'home';

        $page = Page::findCachedBySlug('index');
        if (!$page) {
            throw new Exception("Page 'index' not found");
            return;
        }
        $this->helper->title()->append($page->getMeta_title());
        $this->helper->meta()->set('description', $page->getMeta_description());
        $this->helper->meta()->set('keywords', $page->getMeta_keywords());
        $this->view->page = $page;

        $this->helper->menu->setActive('index');

    }

}
