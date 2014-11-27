<?php

namespace Publication\Controller;

use Application\Mvc\Controller;
use Publication\Model\Publication;
use Phalcon\Exception;
use Publication\Model\Type;

class IndexController extends Controller
{

    public function indexAction()
    {
        $type = $this->dispatcher->getParam('type','string');
        $typeModel = Type::getCachedBySlug($type);
        if (!$typeModel) {
            throw new Exception("Publication hasn't type = '$type''");
        }

        $typeLimit = ($typeModel->getLimit()) ? $typeModel->getLimit() : 10 ;
        $limit = $this->request->getQuery('limit', 'string', $typeLimit);
        if ($limit != 'all') {
            $paginatorLimit = (int) $limit;
        } else {
            $paginatorLimit = 9999;
        }
        $page = $this->request->getQuery('page', 'int', 1);

        $publications = Publication::find(array(
            "type_id = {$typeModel->getId()}",
            "order" => "date DESC",
        ));

        $paginator = new \Phalcon\Paginator\Adapter\Model(array(
            "data" => $publications,
            "limit" => $paginatorLimit,
            "page" => $page
        ));

        $this->view->paginate = $paginator->getPaginate();

        $this->helper->title()->append($typeModel->getHead_title());
        if ($page > 1) {
            $this->helper->title()->append($this->helper->translate('Страница №') . ' ' . $page);
        }
        $this->view->title = $typeModel->getTitle();
        $this->view->format = $typeModel->getFormat();
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
        if ($publication->getTypeSlug() != $type) {
            throw new Exception("Publication type <> $type");
            return;
        }

        $this->helper->title()->append($publication->getMeta_title());
        $this->helper->meta()->set('description', $publication->getMeta_description());
        $this->helper->meta()->set('keywords', $publication->getMeta_keywords());

        $this->view->publication = $publication;

    }

} 