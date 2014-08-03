<?php
/**
 * Created by PhpStorm.
 * User: office-pb1
 * Date: 05.07.14
 * Time: 14:51
 */

namespace Projects\Controller;

use Application\Mvc\Controller;
use Phalcon\Exception;
use Projects\Model\Project;
use Projects\Model\ProjectImage;

class IndexController extends Controller
{

    public function indexAction()
    {
        $limit = $this->request->getQuery('limit', 'string', 6);
        if ($limit != 'all') {
            $paginatorLimit = (int)$limit;
        } else {
            $paginatorLimit = 9999;
        }
        $page = $this->request->getQuery('page', 'int', 1);

        $projects = Project::find(array("visible = 1", array(
            "order" => "sortorder DESC",
        )));

        $paginator = new \Phalcon\Paginator\Adapter\Model(array(
            "data" => $projects,
            "limit" => $paginatorLimit,
            "page" => $page
        ));

        $this->view->paginate = $paginator->getPaginate();

        $this->helper->title()->append('Галерея проектов');
        $this->view->navigationActive = 'projects';
        $this->view->limit = $limit;
    }

    public function projectAction()
    {
        $id = (int)$this->dispatcher->getParam('id', 'int');
        $imagePos = (int)$this->request->getQuery('image', 'int', 1);

        $project = Project::findFirst(array(
            "id = $id AND visible = '1'", array(
                'cache' => array(
                    'key' => md5("Project::findById($id)"),
                    'lifetime' => 60,
                )
            )
        ));
        if (!$project) {
            throw new Exception("Project ID = $id not found");
        }

        $projectImage = ProjectImage::find(array(
            "conditions" => "project_id = $id",
            "order" => "id ASC",
            "limit" => array("number" => 1, "offset" => $imagePos - 1)
        ));

        $this->helper->title()->append($project->getTitle());
        $this->helper->title()->append('Галерея проектов');
        $this->view->imagePos = $imagePos;
        $this->view->project = $project;
        $this->view->projectImage = $projectImage[0];

    }

} 