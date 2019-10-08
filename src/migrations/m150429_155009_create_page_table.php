<?php

use yii\db\Schema;
use yii\db\Migration;
use yii\helpers\Inflector;

/**
 * @author Vasilij Belosludcev http://mihaly4.ru
 * @since 1.0.0
 */
class m150429_155009_create_page_table extends Migration
{
    /**
     * @var string
     */
    private $_tableName;

    public function init()
    {
        parent::init();
        $this->_tableName = Yii::$app->getModule('pages')->tableName;
    }

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable($this->_tableName, [
            'id'               => $this->primaryKey(),
            'title'            => $this->string()->notNull(),
            'display_title'    => $this->boolean()->defaultValue(1),
            'alias'            => $this->string()->notNull(),
            'published'        => $this->boolean()->defaultValue(1),
            'content'          => $this->text(),
            'title_browser'    => $this->string(),
            'meta_keywords'    => $this->string(200),
            'meta_description' => $this->string(160),
            'created_at'       => $this->integer()->notNull(),
            'updated_at'       => $this->integer()->notNull(),
        ],
            $tableOptions
        );

        $baseIndex = strtolower(Inflector::classify($this->_tableName)) . '_idx_';

        $this->createIndex($baseIndex . '1', $this->_tableName, ['alias'], true);
        $this->createIndex($baseIndex . '2', $this->_tableName, ['alias', 'published']);
    }

    public function down()
    {
        $this->dropTable($this->_tableName);
    }
}
