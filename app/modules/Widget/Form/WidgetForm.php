<?php

/**
 * WidgetForm
 * @copyright Copyright (c) 2011 - 2013 Aleksandr Torosh (http://wezoom.com.ua)
 * @author Aleksandr Torosh <webtorua@gmail.com>
 */

namespace Widget\Form;

use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;

use Application\Form\Form;
use Phalcon\Validation\Validator\PresenceOf;
use Phalcon\Validation\Validator\Regex;

class WidgetForm extends Form
{

    public function initialize()
    {
        $id = new Text("id");
        $id->addValidator(new PresenceOf(array(
            'message' => 'ID can not be empty.'
        )));
        $id->addValidator(new Regex(array(
            'pattern' => '/[a-z0-9_-]+/i',
            'message' => 'В ID must be a-z 0-9 _ -'
        )));
        $id->setLabel('ID');

        $title = new Text("title");
        $title->setLabel('Title');

        $html = new TextArea("html");
        $html->setLabel('HTML');

        $this->add($id);
        $this->add($title);
        $this->add($html);

    }

}