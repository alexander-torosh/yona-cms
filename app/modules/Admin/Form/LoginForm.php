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
        $login = new Text('login', array(
            'required' => true,
            'placeholder' => 'Enter login',
        ));
        $login->addValidator(new PresenceOf(array('message' => 'Login is required')));
        $this->add($login);

        $password = new Password('password', array(
            'required' => true,
        ));
        $password->addValidator(new PresenceOf(array('message' => 'Password is required')));
        $this->add($password);

    }

}
