<?php
/**
 * @author Alexander Torosh <webtorua@gmail.com>
 */

namespace Web;

use Phalcon\Acl\Adapter\Memory;
use Phalcon\Cache;
use Phalcon\Di\AbstractInjectionAware;
use Phalcon\Events\Manager;

class AclManager extends AbstractInjectionAware
{
    const CACHE_KEY = 'acl-manager';
    const CACHE_LIFETIME = 7200;

    /**
     * @return Memory
     * @throws Cache\Exception\InvalidArgumentException
     */
    public function initAcl(): Memory
    {
        $acl = null;

        /* @var $serverCache Cache */
        $serverCache = $this->getDI()->get('serverCache');
        $cachedContents = $serverCache->get(self::CACHE_KEY);
        if (!$cachedContents) {
            // Read ACL file
            $aclObject = include __DIR__.'/../../web/config/acl.php';
            if ($aclObject) {
                $acl = $aclObject->acl;

                // Save it to serverCache
                $serverCache->set(self::CACHE_KEY, serialize($aclObject->acl), self::CACHE_LIFETIME);
            }
        } else {
            $acl = unserialize($cachedContents);
        }

        $acl->setEventsManager($this->getDI()->get('eventsManager'));

        return $acl;
    }
}
