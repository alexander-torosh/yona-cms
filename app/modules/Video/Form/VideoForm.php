<?php
/**
 * Created by PhpStorm.
 * User: office-pb1
 * Date: 18.07.14
 * Time: 21:44
 */

namespace Video\Form;


use Application\Form\Form;
use Phalcon\Forms\Element\Text;

class VideoForm extends Form
{

    public function initialize()
    {
        $title = new Text('title');
        $title->setLabel('Название');
        $this->add($title);

        $youtube_link = new Text('youtube_link');
        $youtube_link->setLabel('Линк на Youtube');
        $this->add($youtube_link);

        $sortorder = new Text('sortorder');
        $sortorder->setLabel('Сортировка');
        $this->add($sortorder);
    }

} 