<?php
/**
 * Created by PhpStorm.
 * User: office-pb1
 * Date: 07.07.14
 * Time: 22:48
 */

namespace Projects\Form;


use Application\Form\Form;
use Phalcon\Forms\Element\Check;
use Phalcon\Forms\Element\File;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;

class ProjectForm extends Form
{

    public function initialize()
    {
        $title = new Text('title', array('required' => true));
        $title->setLabel('Название объекта');
        $this->add($title);

        $location = new Text('location', array('required' => true));
        $location->setLabel('Расположение, адрес');
        $this->add($location);

        $description = new TextArea('description');
        $description->setLabel('Описание, системы');
        $this->add($description);

        $visible = new Check('visible');
        $visible->setLabel('Отображать');
        $visible->setDefault(1);
        $this->add($visible);

        $sortorder = new Text('sortorder');
        $sortorder->setLabel('Сортировка');
        $this->add($sortorder);

        $image = new File('image');
        $image->setLabel('Загрузить изображение');
        $this->add($image);

    }

} 