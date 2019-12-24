<?php
/**
 * @author Alexander Torosh <webtorua@gmail.com>
 */

namespace Domain\User;

use Core\Domain\DomainModel;
use Domain\User\Exceptions\UserException;
use Domain\User\Specifications\UserPasswordSpecification;
use Domain\User\Specifications\UserSpecification;
use Domain\User\ValueObjects\UserRoles;

class User extends DomainModel
{
    private $id = 0;
    private $email = '';
    private $name = '';
    private $role = UserRoles::DEFAULT_ROLE;
    private $password = '';
    private $password_hash = '';

    private $created_at;
    private $updated_at;

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

    public function buildPasswordHash(): string
    {
        $passwordSpecification = new UserPasswordSpecification($this->password);
        $passwordSpecification->validate();

        return $this->generatePasswordHash();
    }

    /**
     * @throws DomainException
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

    public function passwordDefined(): bool
    {
        return $this->password ? true : false;
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
