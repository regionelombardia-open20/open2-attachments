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
 * Class m210923_100611_add_columns
 */
class m210923_100611_add_columns extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        if (!$this->db->getTableSchema('attach_file_refs')->getColumn('s3_url')) {
            $this->addColumn('attach_file_refs', 's3_url', $this->text()->defaultValue(null));
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        if (!$this->db->getTableSchema('attach_file_refs')->getColumn('s3_url')) {
            $this->dropColumn('attach_file_refs', 's3_url');
        }

        return true;
    }
}
 