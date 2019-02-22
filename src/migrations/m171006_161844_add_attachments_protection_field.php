<?php

/**
 * Lombardia Informatica S.p.A.
 * OPEN 2.0
 *
 *
 * @package    lispa\amos\attachments
 * @category   CategoryName
 */

use yii\db\Migration;
use yii\db\Schema;

/**
 * Class m150127_040544_add_attachments
 */
class m171006_161844_add_attachments_protection_field extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->addColumn('attach_file_refs', 'protected', schema::TYPE_INTEGER." DEFAULT 0");
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropColumn('attach_file_refs', 'protected');
    }
}
