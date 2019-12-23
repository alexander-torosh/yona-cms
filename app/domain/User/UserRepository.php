<?php
/**
 * @author Alexander Torosh <webtorua@gmail.com>
 */

namespace Domain\User;

use Core\Domain\DomainException;
use Core\Domain\DomainRepository;
use DbModel\User as UserModel;

class UserRepository extends DomainRepository
{
    public function insertUserIntoDb(User $user): int
    {
        $userModel = new UserModel();

        $userModel->email = $user->getEmail();
        $userModel->name = $user->getName();

        if ($user->passwordDefined()) {
            $userModel->password_hash = $user->buildPasswordHash();
        }

        $userModel->create();

        return (int) $userModel->id;
    }

    public function fetchUserModelById(int $userID): UserModel
    {
        $result = UserModel::findFirst($userID);
        if (!$result) {
            throw new DomainException("User {$userID} not found.");
        }

        return $result;
    }

    public function fetchUserModelByEmail(string $email): UserModel
    {
        $result = UserModel::findFirstByEmail($email);
        if (!$result) {
            throw new DomainException("User with email = {$email} not found.");
        }

        return $result;
    }
}
