<?php

/**
 * LoginForm
 * @copyright Copyright (c) 2011 - 2014 Aleksandr Torosh (http://wezoom.com.ua)
 * @author Aleksandr Torosh <webtorua@gmail.com>
 */

namespace Admin\Form;

use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Password;
use Phalcon\Validation\Validator\PresenceOf;

class LoginForm extends \Phalcon\Forms\Form
{

    public function initialize()
    {
        $login = new Text('login');
        $login->addValidator(new PresenceOf(array('message' => 'Login is required')));

        $password = new Password('password');
        $password->addValidator(new PresenceOf(array('message' => 'Password is required')));

    }

}
