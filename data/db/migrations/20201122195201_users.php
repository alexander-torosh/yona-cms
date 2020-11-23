<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class Users extends AbstractMigration
{
    public function up(): void
    {
        $table = $this->table('users');

        if (!$table->exists()) {
            $table
                ->addColumn('email', 'string', [
                    'length' => 100,
                    'default' => null,
                    'null' => true,
                ])
                ->addColumn('name', 'string', [
                    'length' => 100,
                    'default' => null,
                    'null' => true,
                ])
                ->addColumn('role', 'enum', [
                    'values' => [
                        'member',
                        'editor',
                        'admin',
                    ],
                    'default' => 'member',
                ])
                ->addColumn('password_hash', 'string', [
                    'length' => 255,
                    'default' => null,
                    'null' => true,
                ])
                ->addColumn('created_at', 'timestamp', [
                    'default' => 'CURRENT_TIMESTAMP',
                ])
                ->addColumn('updated_at', 'timestamp', [
                    'default' => null,
                    'null' => true,
                ]);

            $table->create();

            $table
                ->addIndex('email', ['unique' => true])
                ->addIndex('role')
                ->update();
        }
    }
}
