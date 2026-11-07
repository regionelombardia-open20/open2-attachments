<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    cruscotto-lavoro\platform\common\console\migrations
 * @category   CategoryName
 */

use open20\amos\core\migration\libs\common\MigrationCommon;
use open20\amos\tag\models\Tag;
use yii\db\Migration;

/**
 * Class m180904_084129_add_cl_roles_tags
 */
class m210931_153127_alter_table_attach_gallery_image extends Migration
{

    /**
     * @inheritdoc
     */
    public function safeUp()
    {

        $this->alterColumn('attach_gallery_image','category_id', $this->integer()->defaultValue(null) );
        return true;
    }


    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        return true;
    }
}
