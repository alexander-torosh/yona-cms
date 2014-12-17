<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

class SeoManagerMigration_101 extends Migration
{

    public function up()
    {
        $this->morphTable(
            'seo_manager',
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
                    'custom_name',
                    array(
                        'type' => Column::TYPE_VARCHAR,
                        'size' => 50,
                        'after' => 'id'
                    )
                ),
                new Column(
                    'route',
                    array(
                        'type' => Column::TYPE_VARCHAR,
                        'size' => 50,
                        'after' => 'custom_name'
                    )
                ),
                new Column(
                    'route_ml',
                    array(
                        'type' => Column::TYPE_VARCHAR,
                        'size' => 60,
                        'after' => 'route'
                    )
                ),
                new Column(
                    'module',
                    array(
                        'type' => Column::TYPE_VARCHAR,
                        'size' => 50,
                        'after' => 'route_ml'
                    )
                ),
                new Column(
                    'controller',
                    array(
                        'type' => Column::TYPE_VARCHAR,
                        'size' => 50,
                        'after' => 'module'
                    )
                ),
                new Column(
                    'action',
                    array(
                        'type' => Column::TYPE_VARCHAR,
                        'size' => 50,
                        'after' => 'controller'
                    )
                ),
                new Column(
                    'language',
                    array(
                        'type' => Column::TYPE_VARCHAR,
                        'size' => 50,
                        'after' => 'action'
                    )
                ),
                new Column(
                    'route_params_json',
                    array(
                        'type' => Column::TYPE_TEXT,
                        'size' => 1,
                        'after' => 'language'
                    )
                ),
                new Column(
                    'query_params_json',
                    array(
                        'type' => Column::TYPE_TEXT,
                        'size' => 1,
                        'after' => 'route_params_json'
                    )
                ),
                new Column(
                    'head_title',
                    array(
                        'type' => Column::TYPE_VARCHAR,
                        'size' => 500,
                        'after' => 'query_params_json'
                    )
                ),
                new Column(
                    'meta_description',
                    array(
                        'type' => Column::TYPE_VARCHAR,
                        'size' => 500,
                        'after' => 'head_title'
                    )
                ),
                new Column(
                    'meta_keywords',
                    array(
                        'type' => Column::TYPE_VARCHAR,
                        'size' => 500,
                        'after' => 'meta_description'
                    )
                ),
                new Column(
                    'seo_text',
                    array(
                        'type' => Column::TYPE_TEXT,
                        'size' => 1,
                        'after' => 'meta_keywords'
                    )
                ),
                new Column(
                    'created_at',
                    array(
                        'type' => Column::TYPE_DATETIME,
                        'size' => 1,
                        'after' => 'seo_text'
                    )
                ),
                new Column(
                    'updated_at',
                    array(
                        'type' => Column::TYPE_DATETIME,
                        'size' => 1,
                        'after' => 'created_at'
                    )
                )
            ),
            'indexes' => array(
                new Index('PRIMARY', array('id'))
            ),
            'options' => array(
                'TABLE_TYPE' => 'BASE TABLE',
                'AUTO_INCREMENT' => '2',
                'ENGINE' => 'InnoDB',
                'TABLE_COLLATION' => 'utf8_general_ci'
            )
        )
        );
    }
}
