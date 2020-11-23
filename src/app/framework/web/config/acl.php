<?php
/**
 * @author Alexander Torosh <webtorua@gmail.com>
 */
use Phalcon\Acl\Adapter\Memory;
use Phalcon\Acl\Role;

class Acl
{
    // Public property for accessing configured ACL
    public Memory $acl;

    public function __construct()
    {
        $acl = new Memory();

        // Roles
        $admin = new Role('admin', 'Website Administrator');
        $editor = new Role('editor', 'Website Editor');
        $guest = new Role('guest', 'Website Guest');

        // Add Guest
        $acl->addRole($guest);

        // Add Editor inheriting access from Guest
        $acl->addRole($editor, $guest);

        // Add Admin inheriting access from Editor
        $acl->addRole($admin, $editor);

        $this->acl = $acl;
    }
}

// Return initialized object from class
return new Acl();
