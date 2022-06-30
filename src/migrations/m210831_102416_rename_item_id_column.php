<?php

use yii\db\Migration;
use yii\db\Schema;

class m210831_102416_rename_item_id_column extends Migration
{

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $table = \Yii::$app->db->schema->getTableSchema('attach_file');
        if (isset($table->columns['itemId'])) {
            $this->execute('ALTER TABLE `attach_file` CHANGE COLUMN `itemId` `item_id` INTEGER(11) NULL DEFAULT NULL;');
        }
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $table = \Yii::$app->db->schema->getTableSchema('attach_file');
        if (isset($table->columns['item_id'])) {
            $this->execute('ALTER TABLE `attach_file` CHANGE COLUMN `item_id` `itemId` INTEGER(11) NULL DEFAULT NULL;');
        }
    }
}