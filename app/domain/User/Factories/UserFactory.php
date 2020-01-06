<?php

namespace Domain\User\Factories;

use Domain\User\Exceptions\UserException;
use Domain\User\Specifications\UserSpecification;
use Domain\User\User;
use Domain\User\UserRepository;
use stdClass;

class UserFactory
{
    public static function create(stdClass $data): User
    {
        $filteredObject = UserFilterFactory::sanitizeCreationData($data);

        $user = new User();
        $user
            ->setEmail($filteredObject->email)
            ->setName($filteredObject->name)
            ->setPassword($filteredObject->password)
        ;

        $userSpecification = new UserSpecification($user);
        $userSpecification->validateNew();

        return $user;
    }

    public static function retrieveById(int $id): User
    {
        $repository = new UserRepository();

        $user = $repository->fetchUserModelById($id);
        if (!$user) {
            throw new UserException("User {$id} not found.");
        }

        return $user;
    }

    public static function retrieveByEmail(string $email): User
    {
        $repository = new UserRepository();

        $user = $repository->fetchUserModelByEmail($email);
        if (!$user) {
            throw new UserException("User with email = {$email} not found.");
        }

        return $user;
    }
}
