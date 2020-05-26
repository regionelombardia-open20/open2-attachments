<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    Open20Package
 * @category   CategoryName
 */

use yii\db\Migration;
use yii\db\Schema;

class m190401_180611_alter_column_attach_file extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->alterColumn('attach_file','itemId', $this->integer()->defaultValue(null));
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        return true;
    }
}
