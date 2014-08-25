<?php
/**
 * Created by PhpStorm.
 * User: office-pb1
 * Date: 18.07.14
 * Time: 21:42
 */

namespace Video\Model;


use Phalcon\Mvc\Model;

class Video extends Model
{

    public function getSource()
    {
        return "video";
    }

    public $id;
    public $title;
    public $youtube_link;
    public $sortorder;

    public function afterCreate()
    {
        $this->sortorder = $this->getUpperSortorder() + 1;

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
     * @param mixed $youtube_link
     */
    public function setYoutubeLink($youtube_link)
    {
        $this->youtube_link = $youtube_link;
    }

    /**
     * @return mixed
     */
    public function getYoutubeLink()
    {
        return $this->youtube_link;
    }

    public function getYoutubeHash()
    {
        if ($this->youtube_link) {
            $arr = explode('?v=', $this->youtube_link);
            if (isset($arr[1])) {
                return $arr[1];
            }
        }
    }

    public function getYoutubeImageSrc()
    {
        return "http://img.youtube.com/vi/{$this->getYoutubeHash()}/hqdefault.jpg";
    }

} 