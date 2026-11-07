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
use yii\db\Schema;

/**
 * Class m150128_160611_add_num_downloads
 */
class m150128_160611_add_num_downloads extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        if (!$this->db->getTableSchema('attach_file')->getColumn('num_downloads')) {
            $this->addColumn('attach_file', 'num_downloads', Schema::TYPE_INTEGER . " DEFAULT 0");
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        if (!$this->db->getTableSchema('attach_file')->getColumn('num_downloads')) {
            $this->dropColumn('attach_file', 'num_downloads');
        }

        return true;
    }
}
