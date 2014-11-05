<?php

/**
 * AdminUser
 * @copyright Copyright (c) 2011 - 2014 Aleksandr Torosh (http://wezoom.com.ua)
 * @author Aleksandr Torosh <webtorua@gmail.com>
 */

namespace Admin\Form;

use Application\Form\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Email;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Check;
use Phalcon\Validation\Validator\Email as ValidatorEmail;
use Phalcon\Validation\Validator\PresenceOf;

class AdminUserForm extends Form
{

    public function initialize()
    {
        $login = new Text('login', array(
            'required' => true,
            'autocomplete' => 'off',
        ));
        $login->setLabel('Login');

        $email = new Email('email', array(
            'required' => true,
            'autocomplete' => 'off',
        ));
        $email->addValidator(new ValidatorEmail(array(
            'message' => 'Email format required',
        )));
        $email->addValidator(new PresenceOf(array(
            'message' => 'Email is required',
        )));
        $email->setLabel('Email');

        $password = new Password('password', array(
            'autocomplete' => 'off',
        ));
        $password->setLabel('Password');

        $active = new Check('active');
        $active->setLabel('Active');

        $this->add($login);
        $this->add($email);
        $this->add($password);
        $this->add($active);

    }

    public function initAdding()
    {
        $password = $this->get('password');
        $password->setAttribute('required', true);
        $password->addValidator(new PresenceOf(array(
            'message' => 'Password is required',
        )));

    }

}
