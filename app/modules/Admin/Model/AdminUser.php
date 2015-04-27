<?php

/**
 * AdminUser
 * @copyright Copyright (c) 2011 - 2014 Aleksandr Torosh (http://wezoom.com.ua)
 * @author Aleksandr Torosh <webtorua@gmail.com>
 */

namespace Admin\Model;

use Phalcon\Mvc\Model\Validator\Uniqueness;
use stdClass;

require_once __DIR__ . '/../../../../vendor/password.php';

class AdminUser extends \Phalcon\Mvc\Model
{

    public function getSource()
    {
        return "admin_user";

    }

    public $id;
    public $login;
    public $email;
    public $password;
    public $active = 0;

    public function beforeValidation()
    {
        if (isset($_POST['active'])) {
            $this->setActive(1);
        } else {
            $this->setActive(0);
        }

    }

    public function validation()
    {
        $this->validate(new Uniqueness(
            array(
                "field" => "login",
                "message" => $this->getDi()->get('helper')->translate("The Login must be unique")
            )
        ));
        
        $this->validate(new Uniqueness(
                array(
            "field"   => "email",
            "message" => $this->getDi()->get('helper')->translate("The Email must be unique")
                )
        ));

        return $this->validationHasFailed() != true;

    }

    public function getId()
    {
        return $this->id;

    }

    public function getLogin()
    {
        return $this->login;

    }

    public function getEmail()
    {
        return $this->email;

    }

    public function getPassword()
    {
        return $this->password;

    }

    public function checkPassword($password)
    {
        if (password_verify($password, $this->password)) {
            return true;
        }

    }

    public function getActive()
    {
        return $this->active;

    }

    public function isActive()
    {
        if ($this->active) {
            return true;
        }

    }

    public function setLogin($login)
    {
        $this->login = $login;

    }

    public function setEmail($email)
    {
        $this->email = $email;

    }

    public function setPassword($password)
    {
        if ($password) {
            $this->password = password_hash($password, PASSWORD_DEFAULT);
        }

    }

    public function setActive($active)
    {
        $this->active = $active;

    }

    public function getPopulateData()
    {
        $data         = new \stdClass();
        $data->login  = $this->login;
        $data->email  = $this->email;
        $data->active = $this->active;
        return $data;

    }

    public function getAuthData()
    {
        $authData                = new stdClass();
        $authData->admin_session = true;
        $authData->login         = $this->login;
        $authData->email         = $this->email;
        return $authData;

    }

}
