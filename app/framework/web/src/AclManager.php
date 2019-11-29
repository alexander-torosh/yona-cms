<?php
/**
 * @author Alexander Torosh <webtorua@gmail.com>
 */

namespace Web;

use Phalcon\Acl\Adapter\Memory;
use Phalcon\Cache;
use Phalcon\Di\AbstractInjectionAware;
use Phalcon\Di\DiInterface;
use Phalcon\Events\Manager;

class AclManager extends AbstractInjectionAware
{
    const CACHE_KEY = 'acl-manager';
    const CACHE_LIFETIME = 7200;

    // @var $acl Memory
    private $acl;

    /**
     * AclManager constructor.
     */
    public function __construct(DiInterface $container, Manager $eventsManager)
    {
        $this->setDI($container);
        $this->init();
        $this->acl->setEventsManager($eventsManager);
    }

    public function init()
    {
        // @var $serverCache Cache
        $serverCache = $this->getDI()->get('serverCache');
        $cachedContents = $serverCache->get(self::CACHE_KEY);
        if (!$cachedContents) {
            // Read ACL file
            $aclObject = include __DIR__.'/../../web/config/acl.php';
            if ($aclObject) {
                $this->acl = $aclObject->acl;

                // Save it to serverCache
                $serverCache->set(self::CACHE_KEY, serialize($aclObject->acl), self::CACHE_LIFETIME);
            }
        } else {
            $this->acl = unserialize($cachedContents);
        }
    }

    public function getAcl()
    {
        return $this->acl;
    }
}
