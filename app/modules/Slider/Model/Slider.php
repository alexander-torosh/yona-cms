<?php
/**
 * Created by PhpStorm.
 * User: office-pb1
 * Date: 07.07.14
 * Time: 22:27
 */

namespace Slider\Model;

use Application\Mvc\Model;

class Slider extends Model
{

    public function getSource()
    {
        return "slider";
    }

    public $id;
    public $title;
    public $animation_speed;
    public $delay;
    public $visible;

    public function initialize()
    {
        $this->hasMany("id", "Slider\Model\SliderImage", "slider_id", array(
            'alias' => 'SliderImages'
        ));

    }

    public function afterCreate()
    {

    }

    public function beforeValidation()
    {
        $this->setVisible( (isset($_POST['visible'])) ? 1 : 0 );
    }

    public function afterValidation()
    {

    }

    public function afterValidationOnCreate()
    {

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
     * @param mixed $animation_speed
     */
    public function setAnimationSpeed($animation_speed)
    {
        $this->animation_speed = $animation_speed;
    }

    /**
     * @return mixed
     */
    public function getAnimationSpeed()
    {
        return $this->animation_speed;
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
     * @param mixed $delay
     */
    public function setDelay($delay)
    {
        $this->delay = $delay;
    }

    /**
     * @return mixed
     */
    public function getDelay()
    {
        return $this->delay;
    }

    public function getFirstImageId()
    {
        $images = $this->getRelated('SliderImages');
        if (!empty($images) && isset($images[0])) {
            $image = $images[0];
            return $image->getId();
        }
    }

}