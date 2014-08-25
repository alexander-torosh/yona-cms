<?php
/**
 * Created by PhpStorm.
 * User: office-pb1
 * Date: 07.07.14
 * Time: 22:27
 */

namespace Projects\Model;

use Phalcon\Mvc\Model;

class ProjectImage extends Model
{

    public function getSource()
    {
        return "project_image";
    }

    public $id;
    public $project_id;

    public function initialize()
    {
        $this->belongsTo("project_id", "Projects\Model\Project", "id", array(
            'alias' => 'Project'
        ));
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $project_id
     */
    public function setProjectId($project_id)
    {
        $this->project_id = $project_id;
    }

    /**
     * @return mixed
     */
    public function getProjectId()
    {
        return $this->project_id;
    }



} 