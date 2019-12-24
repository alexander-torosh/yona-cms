<?php

namespace Domain\User\Factories;

use Core\Domain\DomainFilterFactory;
use stdClass;

class UserFilterFactory extends DomainFilterFactory
{
    public static function sanitizeCreationData(stdClass $data): stdClass
    {
        $locator = parent::getFilterLocator();

        $result = new stdClass();
        $result->email = '';
        $result->name = '';
        $result->role = 'member';
        $result->password = '';

        if (isset($data->email)) {
            $result->email = $data->email ? $locator->sanitize($data->email, 'email') : '';
        }
        if (isset($data->name)) {
            $result->name = $data->name ? $locator->sanitize($data->name, 'strip_tags') : '';
        }
        if (isset($data->role)) {
            $result->role = $data->role ? $locator->sanitize($data->role, 'strip_tags') : 'member';
        }
        if (isset($data->password)) {
            $result->password = $data->password ? $data->password : '';
        }

        return $result;
    }

    public static function sanitizeRetrievingParams(array $params = []): stdClass
    {
        $locator = parent::getFilterLocator();

        $id = 0;
        $email = '';

        if (isset($params['id'])) {
            $id = $params['id'] ? (int) $locator->sanitize($params['id'], 'int') : 0;
        }
        if (isset($params['email'])) {
            $email = $params['email'] ? $locator->sanitize($params['email'], 'email') : '';
        }

        $result = new stdClass();
        $result->id = $id;
        $result->email = $email;

        return $result;
    }
}
