<?php
/**
 * @author Alexander Torosh <webtorua@gmail.com>
 */

namespace Domain\User\UseCase;

use Exception;
use Domain\Core\DomainException;
use Domain\User\Repository\UserRepository;
use Domain\User\Validation\UserPasswordValidation;

class UserPasswordCase
{
    private $repository;

    /**
     * UserPasswordCase constructor.
     */
    public function __construct()
    {
        $this->repository = new UserRepository();
    }

    /**
     * @param int $userID
     * @param string $password
     * @throws DomainException
     */
    public function updatePassword(int $userID, string $password)
    {
        // Validate password strength
        UserPasswordValidation::validate($password);

        // Build UserPassword object
        $passwordHash = $this->buildPasswordHash($userID, $password);

        // Save changes
        $this->repository->updateUserPassword($userID, $passwordHash);
    }

    /**
     * @param int $userID
     * @param string $inputPassword
     * @return bool
     * @throws DomainException
     */
    public function isPasswordMatch(int $userID, string $inputPassword): bool
    {
        $user = $this->repository->fetchUser($userID);
        return password_verify($inputPassword, $user->getPasswordHash());
    }

    /**
     * @param int $userID
     * @param string $password
     * @return string
     * @throws DomainException
     */
    public function buildPasswordHash(int $userID, string $password): string
    {
        return password_hash($password, PASSWORD_ARGON2I, [
            'salt' => $this->generateSalt($userID),
        ]);
    }

    /**
     * @param int $userID
     * @return string
     * @throws DomainException
     */
    private function generateSalt(int $userID): string
    {
        try {
            $length = random_int(16, 32);
            return substr(md5($userID . microtime()), 0, $length);

        } catch (Exception $e) {
            throw new DomainException($e->getMessage());
        }
    }
}