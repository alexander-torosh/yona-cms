<?php
/**
 * @author Alexander Torosh <webtorua@gmail.com>
 */

namespace Domain\User\Specifications;

use Domain\User\Exceptions\UserSpecificationException;

class UserPasswordSpecification
{
    private $password;

    public function __construct($password)
    {
        $this->password = trim(strval($password));
    }

    public function validate()
    {
        $this->validateLength();
        $this->validateLetters();
        $this->validateNumbers();
        $this->validateCaseDiff();
        $this->validateWeakPasswords();
    }

    private function validateLength()
    {
        $length = mb_strlen($this->password);

        if ($length < 8) {
            throw new UserSpecificationException('Password length must be at least 8 characters.');
        }
    }

    private function validateLetters()
    {
        if (0 === preg_match('/\pL/u', $this->password)) {
            throw new UserSpecificationException('Password must contain at least one letter.');
        }
    }

    private function validateNumbers()
    {
        if (0 === preg_match('/\pN/u', $this->password)) {
            throw new UserSpecificationException('Password must contain at least one number.');
        }
    }

    private function validateCaseDiff()
    {
        if (0 === preg_match('/(\p{Ll}+.*\p{Lu})|(\p{Lu}+.*\p{Ll})/u', $this->password)) {
            throw new UserSpecificationException('Password must contain at least one uppercase and one lowercase letter.');
        }
    }

    private function validateWeakPasswords()
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

        if (array_key_exists($this->password, $weakList)) {
            throw new UserSpecificationException('Your password doesn\'t meet our minimum requirements. Please enter a stronger password.');
        }
    }
}
