<?php
/**
 * @author Alexander Torosh <webtorua@gmail.com>
 */

namespace Model;

use Core\Model;

class User extends Model
{
    private $id;
    private $email;
    private $name;
    private $role;
    private $password_hash;
    private $password_salt;

    private $created_at;
    private $updated_at;

    public static $roles = [
        'member',
        'editor',
        'admin'
    ];

    public function initialize()
    {
        $this->setSource('users');
    }

    public function validation()
    {
        // @TODO add role validation
    }
}