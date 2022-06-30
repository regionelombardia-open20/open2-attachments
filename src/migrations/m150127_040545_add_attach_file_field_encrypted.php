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
 * Class m191118_222733_add_attach_file_field_encrypted
 */
class m150127_040545_add_attach_file_field_encrypted extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        if(!$this->db->getTableSchema('attach_file')->getColumn('encrypted')) {
            $this->addColumn('attach_file', 'encrypted', $this->boolean()->notNull()->defaultValue(0));
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        if($this->db->getTableSchema('attach_file')->getColumn('encrypted')) {
            $this->dropColumn('attach_file', 'encrypted');
        }

        return true;
    }
}
