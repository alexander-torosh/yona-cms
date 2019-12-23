<?php

namespace Domain\User\Factories;

use Domain\User\Exceptions\UserException;
use Domain\User\Specifications\UserSpecification;
use Domain\User\User;
use Domain\User\UserRepository;

class UserFactory
{
    public static function create(array $data = []): User
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

    public static function retrieve(array $params = [], $di): User
    {
        $filteredParams = UserFilterFactory::sanitizeRetrievingParams($params);
        $repository = new UserRepository($di);

        if ($filteredParams->userID) {
            if ($filteredParams->email) {
                throw new UserException('Only one retrieving param should be defined: userID or email.');
            }

            $userModel = $repository->fetchUserModelById($filteredParams->userID);
        } elseif ($filteredParams->email) {
            $userModel = $repository->fetchUserModelByEmail($filteredParams->email);
        } else {
            throw new UserException('Bad params for retrieving User');
        }

        $user = new User();
        $user
            ->setUserId($userModel->id)
            ->setEmail($userModel->email)
            ->setName($userModel->name)
        ;

        $userSpecification = new UserSpecification($user);
        $userSpecification->validate();

        return $user;
    }
}
