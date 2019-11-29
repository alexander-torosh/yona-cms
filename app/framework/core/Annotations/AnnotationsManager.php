<?php
/**
 * @author Alexander Torosh <webtorua@gmail.com>
 */

namespace Core\Annotations;

use Phalcon\Annotations\Adapter\Apcu;
use Phalcon\Annotations\Adapter\Memory;
use Phalcon\Di\AbstractInjectionAware;
use Phalcon\Di\DiInterface;

class AnnotationsManager extends AbstractInjectionAware
{
    const CACHE_LIFETIME = 7200;

    private $annotations;

    public function __construct(DiInterface $container)
    {
        $this->setDI($container);
        $this->init();
    }

    public function getAnnotations()
    {
        return $this->annotations;
    }

    private function init()
    {
        if ('production' === getenv('APP_ENV')) {
            $annotations = new Apcu([
                'lifetime' => self::CACHE_LIFETIME,
            ]);
        } else {
            $annotations = new Memory();
        }
        $this->annotations = $annotations;
    }
}
