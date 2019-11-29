<?php
/**
 * @author Alexander Torosh <webtorua@gmail.com>
 */

namespace Model;

use Core\Model;

class User extends Model
{
    public static $roles = [
        'member',
        'editor',
        'admin',
    ];
    private $id = 0;
    private $email = '';
    private $name = '';
    private $role = 'member';
    private $password_hash = '';

    private $created_at;
    private $updated_at;

    public function initialize()
    {
        $this->setSource('users');
    }

    public function validation()
    {
        // @TODO add role validation
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): User
    {
        $this->id = $id;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): User
    {
        $this->email = $email;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): User
    {
        $this->name = $name;

        return $this;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function setRole(string $role): User
    {
        $this->role = $role;

        return $this;
    }

    public function getPasswordHash(): string
    {
        return $this->password_hash;
    }

    public function setPasswordHash(string $password_hash): User
    {
        $this->password_hash = $password_hash;

        return $this;
    }

    public function getCreatedAt(): ?string
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): ?string
    {
        return $this->updated_at;
    }
}
