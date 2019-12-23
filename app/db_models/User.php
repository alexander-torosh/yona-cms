<?php
/**
 * @author Alexander Torosh <webtorua@gmail.com>
 */

namespace DbModel;

use Phalcon\Mvc\Model as MvcModel;

class User extends MvcModel
{
    public static $roles = [
        'member',
        'editor',
        'admin',
    ];
    public $id = 0;
    public $email = '';
    public $name = '';
    public $role = 'member';
    public $password_hash = '';

    public $created_at;
    public $updated_at;

    public function initialize()
    {
        $this->setSource('users');
    }
}
