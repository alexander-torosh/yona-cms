<?php

namespace Publication\Controller;

use Application\Mvc\Controller;
use Publication\Model\Publication;
use Phalcon\Exception;

class IndexController extends Controller
{

    public function indexAction()
    {
        $type = $this->dispatcher->getParam('type','string');
        if (!$type || !in_array($type, array_keys(Publication::$types))) {
            throw new Exception("Publication hasn't type = '$type''");
        }

        $limit = $this->request->getQuery('limit', 'string', 10);
        if ($limit != 'all') {
            $paginatorLimit = (int) $limit;
        } else {
            $paginatorLimit = 9999;
        }
        $page = $this->request->getQuery('page', 'int', 1);

        $publications = Publication::find(array(
            "type = '$type'",
            "order" => "date DESC",
        ));

        $paginator = new \Phalcon\Paginator\Adapter\Model(array(
            "data" => $publications,
            "limit" => $paginatorLimit,
            "page" => $page
        ));

        $this->view->paginate = $paginator->getPaginate();

        $title = Publication::$types[$type];
        $this->helper->title()->append($title);
        $this->view->title = $title;
        //$this->view->limit = $limit;
        $this->view->type = $type;
    }

    public function publicationAction()
    {
        $slug = $this->dispatcher->getParam('slug','string');
        $type = $this->dispatcher->getParam('type','string');

        $publication = Publication::findCachedBySlug($slug);
        if (!$publication) {
            throw new Exception("Publication '$slug.html' not found");
            return;
        }
        if ($publication->getType() != $type) {
            throw new Exception("Publication type <> $type");
            return;
        }

        $this->helper->title()->append($publication->getMeta_title());
        $this->helper->meta()->set('description', $publication->getMeta_description());
        $this->helper->meta()->set('keywords', $publication->getMeta_keywords());

        $this->view->publication = $publication;

    }

} 