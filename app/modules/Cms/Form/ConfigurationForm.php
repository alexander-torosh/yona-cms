<?php
/**
 * @copyright Copyright (c) 2011 - 2014 Aleksandr Torosh (http://wezoom.net)
 * @author Aleksandr Torosh <webtorua@gmail.com>
 */

namespace Cms\Form;

use Application\Form\Form;
use Phalcon\Forms\Element\Text;

class ConfigurationForm extends Form
{

    public function initialization()
    {
        $this->add((new Text('debug_mode'))->setLabel('Режим отладки'));

        $this->add((new Text('technical_works'))->setLabel('Режим "На сайте проводятся технические работы"'));
    }

} 