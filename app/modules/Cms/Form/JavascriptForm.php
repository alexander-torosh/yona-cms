<?php
/**
 * @copyright Copyright (c) 2011 - 2014 Oleksandr Torosh (http://wezoom.net)
 * @author Oleksandr Torosh <web@wezoom.net>
 */

namespace Cms\Form;

use Application\Form\Form;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Forms\Element\Hidden;

class JavascriptForm extends Form
{

    public function initialize()
    {
        $style = 'height:300px;font-size:13px';
        $this->add((new TextArea('head', array('style' => $style)))->setLabel(htmlentities('<head>')));
        $this->add((new TextArea('body', array('style' => $style)))->setLabel(htmlentities('<body>')));

    }

} 