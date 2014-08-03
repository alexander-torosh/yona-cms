<?php

namespace Widget\Model;

class Widget extends \Phalcon\Mvc\Model
{

    public $id;
    public $title;
    public $html;

    public function getId()
    {
        return $this->id;

    }

    public function setId($id)
    {
        $this->id = $id;

    }

    public function getTitle()
    {
        return $this->title;

    }

    public function setTitle($title)
    {
        $this->title = $title;

    }

    public function getHtml()
    {
        return $this->html;

    }

    public function setHtml($html)
    {
        $this->html = $html;

    }

    public function getCsrf()
    {
        return $this->getDi()->get('security')->getToken();
    }

}