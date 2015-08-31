<?php
/**
 * Created by PhpStorm.
 * User: office-pb1
 * Date: 07.07.14
 * Time: 22:48
 */

namespace Page\Form;

use Application\Form\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;

class PageForm extends Form
{

    public function initialize()
    {
        $title = new Text('title', array('required' => true));
        $title->setLabel('Title');
        $this->add($title);

        $slug = new Text('slug');
        $slug->setLabel('Slug');
        $this->add($slug);

        $text = new TextArea('text');
        $text->setLabel('Text');
        $this->add($text);

        $meta_title = new Text('meta_title', array('required' => true));
        $meta_title->setLabel('meta-title');
        $this->add($meta_title);

        $meta_description = new TextArea('meta_description');
        $meta_description->setLabel('meta-description');
        $this->add($meta_description);

        $meta_keywords = new TextArea('meta_keywords');
        $meta_keywords->setLabel('meta-keywords');
        $this->add($meta_keywords);
    }

} 