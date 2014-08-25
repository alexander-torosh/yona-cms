<?php

/**
 * Acl
 * @copyright Copyright (c) 2011 - 2013 Alexander Grigor
 * @author Alexander Grigor <lemannrus@gmail.com>
 */

namespace Widget\Permission;

use Application\Permission\AclAbstract;

class Acl extends AclAbstract
{

    public function __construct()
    {
        parent::__construct();

        $resources = array(
            'widget:admin:index',
            'widget:admin:add',
            'widget:admin:edit',
            'widget:admin:delete',
        );

        foreach ($resources as $resource) {
            $this->addResource($resource);
        }

        return $this;
    }

}

