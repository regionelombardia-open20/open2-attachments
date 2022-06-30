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
        $this->renameColumn('attach_file','itemId', 'item_id');
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->renameColumn('attach_file','item_id', 'itemId');
    }
}
