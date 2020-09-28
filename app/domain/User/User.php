<?php
/**
 * @author Alexander Torosh <webtorua@gmail.com>
 */

namespace Domain\User;

use Core\Domain\DomainModel;
use Domain\User\Exceptions\UserException;
use Domain\User\Specifications\UserPasswordSpecification;
use Domain\User\Specifications\UserSpecification;

class User extends DomainModel
{
    const ROLES = [
        'member',
        'editor',
        'admin',
    ];

    private int $id = 0;
    private string $email = '';
    private string $name = '';
    private string $role = self::ROLES[0];
    private string $password = '';
    private string $password_hash = '';

    private string $created_at;
    private string $updated_at;

    public function initialize()
    {
        $this->setSource('users');

        $this->skipAttributesOnCreate(['id', 'password', 'created_at', 'updated_at']);
        $this->skipAttributesOnUpdate(['password']);
    }

    public function beforeUpdate()
    {
        $this->updated_at = date('Y-m-d H:i:s');
    }

    public function buildPasswordHash()
    {
        $passwordSpecification = new UserPasswordSpecification($this->password);
        $passwordSpecification->validate();

        $this->password_hash = $this->generatePasswordHash();
    }

    /**
     * @param string $inputPassword
     * @return bool
     */
    public function doesPasswordMatch(string $inputPassword): bool
    {
        return password_verify($inputPassword, $this->password_hash);
    }

    public function setId(int $id): User
    {
        if ($this->id > 0) {
            throw new UserException('Identifier `id` is already defined.');
        }
        $this->id = $id;

        $specification = new UserSpecification($this);
        $specification->validateIdentifier();

        return $this;
    }

    public function getId(): int
    {
        return (int) $this->id;
    }

    public function setName(string $name): User
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setEmail(string $email): User
    {
        $this->email = $email;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setPassword(string $password): User
    {
        $this->password = $password;

        return $this;
    }

    public function revealPassword(): string
    {
        return $this->password;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function setRole($role): User
    {
        $this->role = $role;

        return $this;
    }

    private function generatePasswordHash(): string
    {
        return password_hash($this->password, PASSWORD_ARGON2I);
    }
}
