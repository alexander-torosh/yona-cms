<?php
/**
 * @author Alexander Torosh <webtorua@gmail.com>
 */

namespace Domain\User;

use Core\Domain\DomainRepository;
use Domain\User\Exceptions\UserException;

class UserRepository extends DomainRepository
{
    public function insertUserIntoDb(User $user): User
    {
        $user->buildPasswordHash();

        if ($user->create()) {
            return $user;
        }

        foreach ($user->getMessages() as $message) {
            throw new UserException($message);
        }
    }

    public function fetchUserModelById(int $id): ?User
    {
        $result = User::findFirst($id);
        if (!$result) {
            return null;
        }

        return $result;
    }

    public function fetchUserModelByEmail(string $email): ?User
    {
        $result = User::findFirstByEmail($email);
        if (!$result) {
            return null;
        }

        return $result;
    }
}
