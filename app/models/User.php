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
    private $password_hash;
    private $password_salt;

    private $created_at;
    private $updated_at;

    public function initialize()
    {
        $this->setSource('users');
    }
}