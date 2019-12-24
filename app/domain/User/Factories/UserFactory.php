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

    public static function retrieve(array $params = []): User
    {
        $filteredParams = UserFilterFactory::sanitizeRetrievingParams($params);
        $repository = new UserRepository();

        if ($filteredParams->userID) {
            if ($filteredParams->email) {
                throw new UserException('Only one retrieving param should be defined: userID or email.');
            }

            $user = $repository->fetchUserModelById($filteredParams->userID);
            if (!$user) {
                throw new UserException("User {$filteredParams->userID} not found.");
            }
        } elseif ($filteredParams->email) {
            $user = $repository->fetchUserModelByEmail($filteredParams->email);
            if (!$user) {
                throw new UserException("User with email = {$filteredParams->email} not found.");
            }
        } else {
            throw new UserException('Bad params for retrieving User');
        }

        $userSpecification = new UserSpecification($user);
        $userSpecification->validate();

        return $user;
    }
}
