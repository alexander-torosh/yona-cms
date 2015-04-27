<?php
/**
 * @copyright Copyright (c) 2011 - 2014 Aleksandr Torosh (http://wezoom.net)
 * @author Aleksandr Torosh <webtorua@gmail.com>
 */

namespace Cms\Form;

use Application\Form\Form;
use Phalcon\Forms\Element\Check;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Text;

class ConfigurationForm extends Form
{

    public function initialize()
    {
        $this->add((new Check('DEBUG_MODE'))->setDefault(1)->setLabel('Debug mode, display application errors'));
        $this->add((new Check('TECHNICAL_WORKS'))->setDefault(1)->setLabel('The site under maintenance'));
        $this->add((new Check('PROFILER'))->setDefault(1)->setLabel('DB Profiler'));

    }

} 