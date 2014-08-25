<?php
/**
 * Created by PhpStorm.
 * User: office-pb1
 * Date: 07.07.14
 * Time: 22:27
 */

namespace Projects\Model;

use Phalcon\Mvc\Model;

class Project extends Model
{

    public function getSource()
    {
        return "project";
    }

    public $id;
    public $title;
    public $location;
    public $description;
    public $visible = 1;
    public $sortorder;

    public function initialize()
    {
        $this->hasMany("id", "Projects\Model\ProjectImage", "project_id", array(
            'alias' => 'ProjectImages'
        ));
    }

    public function afterCreate()
    {
        $this->sortorder = $this->getUpperSortorder() + 1;

    }

    public function beforeValidation()
    {
        if ($_POST['form']) {
            $this->visible = (isset($_POST['visible'])) ? 1 : 0 ;
        }
    }

    public function afterValidation()
    {
        if (!$this->sortorder) {
            $this->sortorder = $this->getUpperSortorder();
        }

    }

    public function afterValidationOnCreate()
    {
        $this->sortorder = $this->getUpperSortorder() + 1;

    }

    public function getUpperSortorder()
    {
        $count = self::count();
        return $count;

    }


    /**
     * @param int $visible
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;
    }

    /**
     * @return int
     */
    public function getVisible()
    {
        return $this->visible;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $location
     */
    public function setLocation($location)
    {
        $this->location = $location;
    }

    /**
     * @return mixed
     */
    public function getLocation()
    {
        return $this->location;
    }

    public function getLocationCity()
    {
        $arr = explode(',',$this->location);
        return trim($arr[0]);
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
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $sortorder
     */
    public function setSortorder($sortorder)
    {
        $this->sortorder = $sortorder;
    }

    /**
     * @return mixed
     */
    public function getSortorder()
    {
        return $this->sortorder;
    }

    public function getFirstImageId()
    {
        $images = $this->getRelated('ProjectImages');
        if (!empty($images) && isset($images[0])) {
            $image = $images[0];
            return $image->getId();
        }
    }

}