<?php

namespace Index\Controller;

use Application\Mvc\Controller;
use Page\Model\Helper\PageHelper;
use Page\Model\Page;
use Phalcon\Exception;

class IndexController extends Controller
{

    public function indexAction()
    {
        $this->view->bodyClass = 'home';

        $pageHelper = new PageHelper();
        $pageResult = $pageHelper->pageBySlug('index');

        if (!$pageResult) {
            throw new Exception("Page 'index' not found");
        }
        
        $this->helper->title()->append($pageResult->meta_title);
        $this->helper->meta()->set('description', $pageResult->meta_description);
        $this->helper->meta()->set('keywords', $pageResult->meta_keywords);
        
        $this->helper->menu->setActive('index');

        $this->view->text = $pageResult->text;
    }

}
