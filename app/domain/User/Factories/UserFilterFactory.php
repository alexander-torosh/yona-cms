<?php

namespace Domain\User\Factories;

use Core\Domain\DomainFilterFactory;
use stdClass;

class UserFilterFactory extends DomainFilterFactory
{
    public static function sanitizeCreationData(array $data = []): stdClass
    {
        $locator = parent::getFilterLocator();

        $email = $data['email'] ? $locator->sanitize($data['email'], 'email') : '';
        $name = $data['name'] ? $locator->sanitize($data['name'], 'strip_tags') : '';
        $password = $data['password'] ? $data['password'] : '';

        $result = new stdClass();
        $result->email = $email;
        $result->name = $name;
        $result->password = $password;

        return $result;
    }

    public static function sanitizeRetrievingParams(array $params = []): stdClass
    {
        $locator = parent::getFilterLocator();
        $userID = 0;
        $email = '';

        if (isset($params['userID'])) {
            $userID = $params['userID'] ? (int) $locator->sanitize($params['userID'], 'int') : 0;
        }
        if (isset($params['email'])) {
            $email = $params['email'] ? $locator->sanitize($params['email'], 'email') : '';
        }

        $result = new stdClass();
        $result->userID = $userID;
        $result->email = $email;

        return $result;
    }
}
