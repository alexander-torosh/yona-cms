<?php
/**
 * @author Alexander Torosh <webtorua@gmail.com>
 */

namespace Domain\User\Repository;

use Domain\Core\DomainException;
use Domain\User\UseCase\FetchUserCase;
use Model\User;
use Phalcon\Mvc\ModelInterface;

class UserRepository
{
    /**
     * @throws DomainException
     *
     * @return User
     */
    public function fetchUser(int $userID): ModelInterface
    {
        $user = User::findFirst($userID);
        if (!$user) {
            throw new DomainException("User {$userID} not found.");
        }

        return $user;
    }

    /**
     * @throws DomainException
     */
    public function updateUserPassword(int $userID, string $passwordHash)
    {
        $fetchUserCase = new FetchUserCase();
        $user = $fetchUserCase->getUser($userID);
        if (!$user) {
            throw new DomainException("User {$userID} not found.");
        }

        $user->setPasswordHash($passwordHash);

        if (!$user->update()) {
            $messages = $user->getMessages();
            foreach ($messages as $message) {
                throw new DomainException($message);
            }
        }
    }
}
