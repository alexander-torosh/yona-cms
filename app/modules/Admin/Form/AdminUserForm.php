<?php

/**
 * AdminUser
 * @copyright Copyright (c) 2011 - 2014 Aleksandr Torosh (http://wezoom.com.ua)
 * @author Aleksandr Torosh <webtorua@gmail.com>
 */

namespace Admin\Form;

use Admin\Model\AdminUser;
use Application\Form\Form;
use Phalcon\Forms\Element\Select;
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
        $this->add(
            (new Text('login', [
                'required' => true,
            ]))->setLabel('Login')
        );

        $this->add(
            (new Email('email', [
                'required' => true,
            ]))
                ->addValidator(new ValidatorEmail([
                    'message' => 'Email format is invalid',
                ]))
                ->setLabel('Email')
        );

        $this->add(
            (new Text('name'))
                ->setLabel('Name')
        );

        $this->add(
            (new Select('role', AdminUser::$roles))
                ->setLabel('Role')
        );

        $this->add(
            (new Password('password'))
                ->setLabel('Password')
        );

        $this->add(
            (new Check('active'))
                ->setLabel('Active')
        );
    }

    public function initAdding()
    {
        $password = $this->get('password');
        $password->setAttribute('required', true);
        $password->addValidator(new PresenceOf([
            'message' => 'Password is required',
        ]));

    }

}
