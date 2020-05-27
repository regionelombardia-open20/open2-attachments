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

class m200317_100611_add_column_attach_file extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->addColumn('attach_file','table_name_form', $this->text()->defaultValue(null));
    }

    /**
     * @inheritdoc 
     */
    public function safeDown()
    {
        return true;
    }
}
 