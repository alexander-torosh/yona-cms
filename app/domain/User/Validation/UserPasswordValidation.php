<?php
/**
 * @author Alexander Torosh <webtorua@gmail.com>
 */

namespace Domain\User\Validation;

use Domain\Core\DomainException;

class UserPasswordValidation
{
    /**
     * @throws DomainException
     */
    public static function validate(string $password): bool
    {
        $password = trim($password);
        self::doValidation($password);

        // Default result
        return true;
    }

    /**
     * @throws DomainException
     */
    private static function doValidation(string $password)
    {
        if (!self::validateLength($password)) {
            throw new DomainException('Password length must be at least 8 characters.');
        }
        if (!self::validateLetters($password)) {
            throw new DomainException('Password must contain at least one letter.');
        }
        if (!self::validateNumbers($password)) {
            throw new DomainException('Password must contain at least one number.');
        }
        if (!self::validateCaseDiff($password)) {
            throw new DomainException('Password must contain at least one uppercase and one lowercase letter.');
        }
        if (!self::validateWeakPasswords($password)) {
            throw new DomainException('Your password doesn\'t meet our minimum requirements. Please enter a stronger password.');
        }
    }

    private static function validateLength(string $password): bool
    {
        $length = mb_strlen($password);

        return $length >= 8;
    }

    /**
     * @return false|int
     */
    private static function validateLetters(string $password)
    {
        return preg_match('/\pL/u', $password);
    }

    /**
     * @return false|int
     */
    private static function validateNumbers(string $password)
    {
        return preg_match('/\pN/u', $password);
    }

    /**
     * @return false|int
     */
    private static function validateCaseDiff(string $password)
    {
        return preg_match('/(\p{Ll}+.*\p{Lu})|(\p{Lu}+.*\p{Ll})/u', $password);
    }

    private static function validateWeakPasswords(string $password): bool
    {
        $weakList = [
            'password',
            '12345678',
            '123456789',
            '1234567890',
            'qwertyui',
            'baseball',
            'football',
            'abc12345',
            'abcd1234',
            'jennifer',
            '11111111',
            'superman',
            'pussycat',
        ];

        return false === in_array($password, $weakList, true);
    }
}
