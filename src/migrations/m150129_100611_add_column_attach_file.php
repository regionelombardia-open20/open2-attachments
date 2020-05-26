<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    open20\amos\attachments\migrations
 * @category   CategoryName
 */

use yii\db\Migration;

/**
 * Class m150129_100611_add_column_attach_file
 */
class m150129_100611_add_column_attach_file extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        if (!$this->db->getTableSchema('attach_file')->getColumn('table_name_form')) {
            $this->addColumn('attach_file', 'table_name_form', $this->text()->defaultValue(null));
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        if (!$this->db->getTableSchema('attach_file')->getColumn('table_name_form')) {
            $this->dropColumn('attach_file', 'table_name_form');
        }

        return true;
    }
}
 