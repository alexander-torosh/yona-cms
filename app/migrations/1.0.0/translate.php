<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

class TranslateMigration_100 extends Migration
{

    public function up()
    {
        $this->morphTable(
            'translate',
            array(
            'columns' => array(
                new Column(
                    'id',
                    array(
                        'type' => Column::TYPE_INTEGER,
                        'notNull' => true,
                        'autoIncrement' => true,
                        'size' => 11,
                        'first' => true
                    )
                ),
                new Column(
                    'lang',
                    array(
                        'type' => Column::TYPE_VARCHAR,
                        'size' => 20,
                        'after' => 'id'
                    )
                ),
                new Column(
                    'phrase',
                    array(
                        'type' => Column::TYPE_VARCHAR,
                        'size' => 500,
                        'after' => 'lang'
                    )
                ),
                new Column(
                    'translation',
                    array(
                        'type' => Column::TYPE_VARCHAR,
                        'size' => 500,
                        'after' => 'phrase'
                    )
                )
            ),
            'indexes' => array(
                new Index('PRIMARY', array('id'))
            ),
            'options' => array(
                'TABLE_TYPE' => 'BASE TABLE',
                'AUTO_INCREMENT' => '25',
                'ENGINE' => 'InnoDB',
                'TABLE_COLLATION' => 'utf8_general_ci'
            )
        )
        );
    }
}
