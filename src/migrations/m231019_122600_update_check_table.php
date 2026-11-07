<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 *
 */
class m231019_122600_update_check_table extends Migration
{

    const TABLE = "attach_file_not_exists";
    const TABLE_REF = "attach_file";

    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->dropForeignKey('fk_attach_file_id', self::TABLE);
        // $this->createIndex('index_attach_file_id',self::TABLE, 'attach_file_id');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        // $this->dropIndex('index_attach_file_id',self::TABLE);
        $this->addForeignKey('fk_attach_file_id', self::TABLE, 'attach_file_id', self::TABLE_REF, 'id');
    }
}
