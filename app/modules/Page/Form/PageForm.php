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
        $this->add((new Text('title', ['required' => true]))->setLabel('Title'));

        $this->add((new Text('slug'))->setLabel('Slug'));

        $this->add((new Text('head_title'))->setLabel('Head Title'));

        $this->add((new TextArea('meta_description'))->setLabel('Meta Description'));

        $this->add((new TextArea('meta_keywords'))->setLabel('Meta Keywords'));

        $this->add((new TextArea('text'))->setLabel('Text'));
    }

} 