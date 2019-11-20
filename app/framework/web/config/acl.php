<?php
/**
 * @author Alexander Torosh <webtorua@gmail.com>
 */

use Phalcon\Acl\Role;
use Phalcon\Acl\Adapter\Memory;

class Acl
{
    // Public property for accessing configured ACL
    public $acl;

    public function __construct()
    {
        $acl = new Memory();

        // Roles
        $admin  = new Role('admin', 'Website Administrator');
        $editor = new Role('editor', 'Website Editor');
        $guest  = new Role('guest', 'Website Guest');

        // Add Guest
        $acl->addRole($guest);

        // Add Editor inheriting access from Guest
        $acl->addRole($editor, $guest);

        // Add Admin inheriting access from Editor
        $acl->addRole($admin, $editor);

        // Front Index
        /*$acl->addComponent(
            'Front\Controllers\IndexController',
            [
                '*',
            ]
        );

        // Dashboard Index
        $acl->addComponent(
            'Dashboard\Controllers\IndexController',
            [
                'index',
            ]
        );*/

        // Frontend Rules
        // $acl->allow('guest', 'Front\Controllers\IndexController', '*');

        // Dashboard Rules
        // $acl->allow('editor', 'Dashboard\Controllers\IndexController', '*');

        // Save object to acl property
        $this->acl = $acl;
    }
}

// Return initialized object from class
return new Acl();