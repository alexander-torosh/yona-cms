<?php
/**
 * @author Alexander Torosh <webtorua@gmail.com>
 */

namespace Core\Annotations;

use Phalcon\Annotations\Adapter\Apcu;
use Phalcon\Annotations\Adapter\Memory;
use Phalcon\Di\AbstractInjectionAware;

class AnnotationsManager extends AbstractInjectionAware
{
    const CACHE_LIFETIME = 7200;

    /**
     * @return Apcu|Memory
     */
    public function initAnnotations()
    {
        if ('production' === getenv('APP_ENV')) {
            $annotations = new Apcu([
                'lifetime' => self::CACHE_LIFETIME,
            ]);
        } else {
            $annotations = new Memory();
        }
        return $annotations;
    }
}
