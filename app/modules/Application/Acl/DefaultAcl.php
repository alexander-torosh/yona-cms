<?php

/**
 * DefaultAcl
 * @copyright Copyright (c) 2011 - 2014 Aleksandr Torosh (http://wezoom.com.ua)
 * @author Aleksandr Torosh <webtorua@gmail.com>
 */

namespace Application\Acl;

class DefaultAcl extends \Phalcon\Acl\Adapter\Memory
{

    public function __construct()
    {
        parent::__construct();

        $this->setDefaultAction(\Phalcon\Acl::DENY);

        $roles = array(
            'admin'  => new \Phalcon\Acl\Role('admin', 'Администратор'),
            'guest'  => new \Phalcon\Acl\Role('guest', 'Неавторизированный посетитель. Простое посещение'),
            'member' => new \Phalcon\Acl\Role('member', 'Авторизированный посетитель'),
        );
        foreach ($roles as $role) {
            $this->addRole($role);
        }

        $privateResources = array(
            'admin/admin-user',
            'cms/configuration',
            'cms/translate',
            'cms/language',
            'cms/javascript',
            'widget/admin',
            'projects/admin',
            'systems/admin',
            'video/admin',
            'file-manager/index',
            'page/admin',
            'publication/admin',
            'publication/type',
            'slider/admin',
            'seo/robots',
            'seo/manager',
            'tree/tree',
        );
        foreach ($privateResources as $resource) {
            $this->addResource(new \Phalcon\Acl\Resource($resource));
        }

        $publicResources = array(  
            'admin/index',
            'index/index',
            'index/error',
            'projects/index',
            'systems/index',
            'page/index',
            'video/index',
            'publication/index',
        );
        foreach ($publicResources as $resource) {
            $this->addResource(new \Phalcon\Acl\Resource($resource));
        }

        foreach ($roles as $role) {
            foreach ($publicResources as $resource) {
                $this->allow($role->getName(), $resource, '*');
            }
        }

        foreach ($privateResources as $resource) {
            $this->allow('admin', $resource, '*');
        }

    }

}
