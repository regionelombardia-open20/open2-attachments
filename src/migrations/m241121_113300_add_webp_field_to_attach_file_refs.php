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
 * Class m241121_113300_add_webp_field_to_attach_file_refs
 */
class m241121_113300_add_webp_field_to_attach_file_refs extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        if (!$this->db->getTableSchema('attach_file_refs')->getColumn('is_webp')) {
            $this->addColumn('attach_file_refs', 'is_webp', $this->boolean()->defaultValue(0)->after('crop'));
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        if (!$this->db->getTableSchema('attach_file_refs')->getColumn('is_webp')) {
            $this->dropColumn('attach_file_refs', 'is_webp');
        }

        return true;
    }
}
 