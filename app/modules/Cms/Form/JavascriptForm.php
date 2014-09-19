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
        $this->add((new TextArea('text')));
        $this->add((new Hidden  ("id")));

    }

} 