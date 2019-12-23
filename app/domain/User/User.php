<?php
/**
 * @author Alexander Torosh <webtorua@gmail.com>
 */

namespace Domain\User;

use Domain\User\Exceptions\UserException;
use Domain\User\Specifications\UserPasswordSpecification;
use Domain\User\Specifications\UserSpecification;

class User
{
    private $userID = 0;
    private $email = '';
    private $name = '';
    private $password = '';

    public function buildPasswordHash(): string
    {
        $passwordSpecification = new UserPasswordSpecification($this->password);
        $passwordSpecification->validate();

        return $this->generatePasswordHash();
    }

    /**
     * @throws DomainException
     */
    public function doesPasswordMatch(int $userID, string $inputPassword): bool
    {
        $user = $this->repository->fetchUser($userID);

        return password_verify($inputPassword, $user->password_hash);
    }

    public function setUserId(int $userID): User
    {
        if ($this->userID > 0) {
            throw new UserException('Identifier `userID` is already defined.');
        }
        $this->userID = $userID;

        $specification = new UserSpecification($this);
        $specification->validateIdentifier();

        return $this;
    }

    public function setPassword(string $password): User
    {
        $this->password = $password;

        return $this;
    }

    public function setName(string $name): User
    {
        $this->name = $name;

        return $this;
    }

    public function setEmail(string $email): User
    {
        $this->email = $email;

        return $this;
    }

    public function getUserID(): int
    {
        return $this->userID;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function passwordDefined(): bool
    {
        return $this->password ? true : false;
    }

    private function generatePasswordHash(): string
    {
        return password_hash($this->password, PASSWORD_ARGON2I, [
            'salt' => $this->generateSalt(),
        ]);
    }

    private function generateSalt(): string
    {
        $length = random_int(16, 32);

        return substr(md5($this->email.'_'.microtime()), 0, $length);
    }
}
