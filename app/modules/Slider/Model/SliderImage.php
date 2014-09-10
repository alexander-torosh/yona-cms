<?php
/**
 * Created by PhpStorm.
 * User: office-pb1
 * Date: 07.07.14
 * Time: 22:27
 */

namespace Slider\Model;

use Application\Mvc\Model;

class SliderImage extends Model
{

    public function getSource()
    {
        return "slider_image";
    }

    protected $translateModel = 'Slider\Model\Translate\SliderTranslate';

    public $id;
    public $slider_id;
    public $caption;
    public $link;
    public $sortorder;
    public $img_lang; //это язык при котором была загружена картинка. Для каждого языка надо указывать разные картинки

    public function initialize()
    {
        $this->belongsTo("slider_id", "Slider\Model\Slider", "id", array(
            'alias' => 'Slider'
        ));

        $this->hasMany("id", $this->translateModel, "foreign_id");

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
     * @param mixed $slider_id
     */
    public function setSliderId($slider_id)
    {
        $this->slider_id = $slider_id;
    }

    /**
     * @return mixed
     */
    public function getSliderId()
    {
        return $this->slider_id;
    }

    /**
     * @return mixed
     */
    public function getCaption()
    {
        return $this->getMLVariable('caption');
    }

    /**
     * @param mixed $caption
     */
    public function setCaption($caption)
    {
        $this->setMLVariable('caption', $caption);
    }

    /**
     * @return mixed
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param mixed $link
     */
    public function setLink($link)
    {
        $this->link = $link;
    }

    /**
     * @param mixed $sortorder
     */
    public function setSortOrder($sortorder)
    {
        $this->sortorder = $sortorder;
    }

    /**
     * @return mixed
     */
    public function getSortOrder()
    {
        return $this->sortorder;
    }

    /**
     * @param mixed $img_lang
     */
    public function setImgLang($img_lang)
    {
        $this->img_lang = $img_lang;
    }

    /**
     * @return mixed
     */
    public function getImgLang()
    {
        return $this->img_lang;
    }



} 