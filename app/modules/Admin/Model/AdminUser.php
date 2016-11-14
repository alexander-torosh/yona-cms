<?php
/**
 * @copyright Copyright (c) 2011 - 2014 Aleksandr Torosh (http://wezoom.com.ua)
 * @author Aleksandr Torosh <webtorua@gmail.com>
 */

namespace Admin\Model;
use Phalcon\Validation;
use Phalcon\Validation\Validator\Email as EmailValidator;
use Phalcon\Validation\Validator\Uniqueness as UniquenessValidator;
use stdClass;

class AdminUser extends \Phalcon\Mvc\Model
{

    public function getSource()
    {
        return "admin_user";
    }

    public $id;
    public $role;
    public $login;
    public $email;
    public $name;
    public $password;
    public $active = 0;

    public static $roles = [
        'journalist' => 'Journalist',
        'editor'     => 'Editor',
        'admin'      => 'Admin',
    ];

    public function initialize()
    {
        
    }

    public function setCheckboxes($post)
    {
        $this->setActive(isset($post['active']) ? 1 : 0);
    }

    public function validation()
    {

       $validator = new Validation();
       $validator->add('login', new UniquenessValidator(
           [
               "model"   => $this,
               "message" => $this->getDi()->get('helper')->translate("The Login must be unique")
           ]
       ));
       $validator->add('email', new UniquenessValidator(
           [
               "model"   => $this,
               "message" => $this->getDi()->get('helper')->translate("The Email must be unique")
           ]
       ));
       return $this->validate($validator);
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

    public function getRole()
    {
        return $this->role;
    }

    public function getRoleTitle()
    {
        if (array_key_exists($this->role, self::$roles)) {
            return self::$roles[$this->role];
        }
    }

    public function setRole($role)
    {
        $this->role = $role;
    }

    public function getName()
    {
        return $this->name;
    }


    public function setName($name)
    {
        $this->name = $name;
    }

    public function getPassword()
    {
        return ''; // We don't need hash of password. Just return empty string.
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

    public function getAuthData()
    {
        $authData = new stdClass();
        $authData->id = $this->getId();
        $authData->admin_session = true;
        $authData->login = $this->getLogin();
        $authData->email = $this->getEmail();
        $authData->name = $this->getName();
        return $authData;
    }

    public static function getRoleById($id)
    {
        $role = self::findFirst([
            'conditions' => 'id = :id:',
            'bind'       => ['id' => $id],
            'columns'    => ['role'],
            'cache'      => [
                'key'      => HOST_HASH . md5('Admin\Model\AdminUser::getRoleById::' . $id),
                'lifetime' => 60,
            ]
        ]);
        if ($role) {
            return $role->role;
        } else {
            return 'guest';
        }

    }

}
