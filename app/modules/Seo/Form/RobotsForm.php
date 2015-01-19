<?php
 /**
 * @copyright Copyright (c) 2011 - 2014 Oleksandr Torosh (http://wezoom.net)
 * @author Oleksandr Torosh <web@wezoom.net>
 */

namespace Seo\Form;

use Application\Form\Form;
use Phalcon\Forms\Element\TextArea;

class RobotsForm extends Form
{

    public function initialize()
    {
        $this->add((new TextArea('robots'))->setLabel('Contents'));
    }

} 