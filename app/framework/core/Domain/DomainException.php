<?php
/**
 * @author Alexander Torosh <webtorua@gmail.com>
 */

namespace Core\Domain;

class DomainException extends \Exception
{
    protected $code = 422;
}
