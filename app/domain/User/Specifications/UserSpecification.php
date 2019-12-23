<?php

namespace Domain\User\Specifications;

use Domain\User\Exceptions\UserSpecificationException;
use Domain\User\User;

class UserSpecification
{
    /** @var User $user */
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function validate()
    {
        $this->validateIdentifier();
        $this->doBasicValidation();
    }

    public function validateNew()
    {
        $this->doBasicValidation();

        $passwordSpecification = new UserPasswordSpecification($this->user->getPassword());
        $passwordSpecification->validate();
    }

    public function validateIdentifier()
    {
        $userID = $this->user->getUserID();

        if (!is_int($userID)) {
            throw new UserSpecificationException('Property `userID` must be integer.');
        }

        if ($userID <= 0) {
            throw new UserSpecificationException('Property `userID` must be more than 0.');
        }
    }

    private function doBasicValidation()
    {
        $this->validateEmail();
        $this->validateName();
    }

    private function validateEmail()
    {
        $email = $this->user->getEmail();
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new UserSpecificationException('Property `email` is not valid.');
        }
    }

    private function validateName()
    {
        $name = $this->user->getName();
        if (\mb_strlen($name) < 1) {
            throw new UserSpecificationException('`name` length should be more than 1 character.');
        }
        if (\mb_strlen($name) > 50) {
            throw new UserSpecificationException('`name` length should be less than 50 characters.');
        }
    }
}
