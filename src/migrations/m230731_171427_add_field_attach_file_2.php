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
class m230731_171427_add_field_attach_file_2 extends Migration
{


    /**
     * @inheritdoc
     */
    public function safeUp(){
        $this->addColumn('attach_file', 'alt_description', $this->string(255)->after('table_name_form'));
    }



    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('attach_file', 'alt_description');

    }
}
