<?php

/**
 * @author Oleksandr Torosh <webtorua@gmail.com>
 */
namespace Yona;

class Acl extends \Phalcon\Acl\Adapter\Memory
{
    public function __construct()
    {
        parent::__construct();
        $this->setDefaultAction(\Phalcon\Acl::DENY);

        /**
         * Full list of Roles
         */
        $roles = [];
        $roles['guest'] = new \Phalcon\Acl\Role('guest', 'Guest');
        $roles['member'] = new \Phalcon\Acl\Role('member', 'Member');
        $roles['editor'] = new \Phalcon\Acl\Role('editor', 'Editor');
        $roles['admin'] = new \Phalcon\Acl\Role('admin', 'Admin');

        /**
         * Add roles
         */
        $this->addRole($roles['guest']);
        $this->addRole($roles['member'], $roles['guest']);
        $this->addRole($roles['editor'], $roles['member']);
        $this->addRole($roles['admin']);

        /**
         * Include resources permissions list from file /app/config/acl.php
         */
        $resources = include BASE_PATH . '/app/config/acl.php';
        foreach ($resources as $roles_resources) {
            foreach ($roles_resources as $resource => $actions) {
                $registerActions = '*';
                if (is_array($actions)) {
                    $registerActions = $actions;
                }
                $this->addResource(new \Phalcon\Acl\Resource($resource), $registerActions);
            }
        }

        /**
         * Make unlimited access for admin role
         */
        $this->allow('admin', '*', '*');

        /**
         * Set roles permissions
         */
        foreach ($roles as $k => $role) {
            $user_resource = $resources[$k];
            foreach ($user_resource as $roles_resources => $method) {
                if ($method == '*') {
                    $this->allow($k, $roles_resources, '*');
                } else {
                    $this->allow($k, $roles_resources, $method);
                }
            }
        }
    }
}