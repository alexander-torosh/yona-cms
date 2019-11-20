<?php
/**
 * @author Alexander Torosh <webtorua@gmail.com>
 */

namespace Core\Annotations;

use Phalcon\Di\AbstractInjectionAware;
use Phalcon\Di\DiInterface;
use Phalcon\Annotations\Adapter\Apcu;
use Phalcon\Annotations\Adapter\Memory;

class AnnotationsManager extends AbstractInjectionAware
{
    const CACHE_LIFETIME = 60;

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
        if (getenv('APP_ENV') === 'production') {
            $annotations = new Apcu([
                'lifetime' => self::CACHE_LIFETIME,
            ]);
        } else {
            $annotations = new Memory();
        }
        $this->annotations = $annotations;
    }
}