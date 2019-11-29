<?php
/**
 * @author Alexander Torosh <webtorua@gmail.com>
 */

namespace Domain\User\Entity;

use Core\Domain\Entity;

class User extends Entity
{
    private $id = 0;
    private $email = '';
    private $name = '';
    private $role = 'member';

    public function getId(): ?int
    {
        return $this->id ? (int) $this->id : null;
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
}
