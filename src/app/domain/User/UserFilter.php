<?php

namespace Domain\User;

use Core\Domain\DomainFilterFactory;
use stdClass;

class UserFilter extends DomainFilterFactory
{
    public function sanitizeCreationData(stdClass $data): stdClass
    {
        $result = new stdClass();
        $result->email = $this->sanitizeEmail($data);
        $result->name = $this->sanitizeName($data);
        $result->role = $this->sanitizeRole($data);
        $result->password = $this->sanitizePassword($data);

        return $result;
    }

    public function sanitizeAuthParams(stdClass $data): stdClass
    {
        $result = new stdClass();
        $result->email = $this->sanitizeEmail($data);
        $result->password = $this->sanitizePassword($data);
        $result->csrfToken = $data->csrfToken ?? '';
        $result->fingerprintHash = $data->fingerprintHash ?? '';

        $result->rememberMe = isset($data->rememberMe) ? (bool) $data->rememberMe : false;

        return $result;
    }

    private function sanitizeEmail(stdClass $data): string
    {
        if (isset($data->email)) {
            return $this->filterLocator->sanitize($data->email, 'email');
        }

        return '';
    }

    private function sanitizeName(stdClass $data): string
    {
        if (isset($data->name)) {
            return $this->filterLocator->sanitize($data->name, 'strip_tags');
        }

        return '';
    }

    private function sanitizeRole(stdClass $data): string
    {
        if (isset($data->role)) {
            return $this->filterLocator->sanitize($data->role, 'strip_tags');
        }

        return 'member';
    }

    private function sanitizePassword(stdClass $data): string
    {
        if (isset($data->password)) {
            return $data->password;
        }

        return '';
    }
}
