<?php

namespace Publication\Controller;

use Application\Mvc\Controller;
use Publication\Model\Helper\PublicationHelper;
use Publication\Model\Publication;
use Phalcon\Exception;
use Publication\Model\Type;

class IndexController extends Controller
{

    public function indexAction()
    {
        $type      = $this->dispatcher->getParam('type', 'string');
        $typeModel = Type::getCachedBySlug($type);
        if (!$typeModel) {
            throw new Exception("Publication hasn't type = '$type''");
        }

        $typeLimit = ($typeModel->getLimit()) ? $typeModel->getLimit() : 10;
        $limit     = $this->request->getQuery('limit', 'string', $typeLimit);
        if ($limit != 'all') {
            $paginatorLimit = (int)$limit;
        } else {
            $paginatorLimit = 9999;
        }
        $page = $this->request->getQuery('page', 'int', 1);

        /*$publications = Publication::find(array(
            "type_id = {$typeModel->getId()}",
            "order" => "date DESC",
        ));*/

        $publicationHelper = new PublicationHelper();
        $fields = $publicationHelper->translateFieldsSubQuery();

        $columns = ['p.*', 't_slug' => 't.slug'];
        $columns = array_merge($columns, $fields);

        $qb = $this->modelsManager->createBuilder()
            ->columns($columns)
            ->addFrom('Publication\Model\Publication', 'p')
            ->leftJoin('Publication\Model\Type', null, 't')
            ->andWhere('t.slug = :type:', ['type' => $type])
            ->andWhere('p.date <= NOW()')
            ->orderBy('p.date DESC');

        $paginator = new \Phalcon\Paginator\Adapter\QueryBuilder([
            "builder"  => $qb,
            "limit" => $paginatorLimit,
            "page"  => $page
        ]);

        $this->view->paginate = $paginator->getPaginate();

        $this->helper->title()->append($typeModel->getHeadTitle());
        if ($page > 1) {
            $this->helper->title()->append($this->helper->translate('Страница №') . ' ' . $page);
        }
        $this->view->title  = $typeModel->getTitle();
        $this->view->format = $typeModel->getFormat();
        $this->view->type   = $type;

        $this->helper->menu->setActive($type);
    }

    public function publicationAction()
    {
        $slug = $this->dispatcher->getParam('slug', 'string');
        $type = $this->dispatcher->getParam('type', 'string');

        $publicationHelper = new PublicationHelper();
        $publicationResult = $publicationHelper->publicationBySlug($slug);
        if (!$publicationResult) {
            throw new Exception("Publication '$slug.html' not found");
        }
        if ($publicationResult->p->getTypeSlug() != $type) {
            throw new Exception("Publication type <> $type");
        }

        $this->helper->title()->append($publicationResult->meta_title);
        $this->helper->meta()->set('description', $publicationResult->meta_description);
        $this->helper->meta()->set('keywords', $publicationResult->meta_keywords);

        $this->helper->menu->setActive($type);

        $this->view->publicationResult = $publicationResult;
    }

}
