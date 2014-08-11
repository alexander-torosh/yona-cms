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
        $this->add((new Select('APPLICATION_ENV', [
            'development' => 'development',
            'production' => 'production',
        ]))->setLabel('Окружение - APPLICATION_ENV'));

        $this->add((new Check('DEBUG_MODE', ['value' => 1]))->setLabel('Режим отладки, вывод ошибок приложения'));

        $this->add((new Check('TECHNICAL_WORKS', ['value' => 1]))->setLabel('Режим "На сайте проводятся технические работы"'));

    }

} 