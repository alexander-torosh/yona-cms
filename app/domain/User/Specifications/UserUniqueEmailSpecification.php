<?php

namespace Domain\User\Specifications;

use Domain\User\Exceptions\UserSpecificationException;
use Domain\User\User;
use Domain\User\UserRepository;

class UserUniqueEmailSpecification
{
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function validate()
    {
        $repository = new UserRepository();
        $existingUser = $repository->fetchUserModelByEmail($this->user->getEmail());
        if ($existingUser) {
            if ($this->user->getId() !== $existingUser->getId()) {
                throw new UserSpecificationException("Email {$existingUser->getEmail()} already exists in the system.");
            }
        }
    }
}
