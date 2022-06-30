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
class m210931_151929_fix_role_permission extends Migration
{


    /**
     * @inheritdoc
     */
    public function safeUp()
    {


        $this->update('auth_item', ['type' => 1], ['name' => 'ATTACH_GALLERY_ADMINISTRATOR']);
        $this->update('auth_item', ['type' => 1], ['name' =>  'ATTACH_GALLERY_READER' ]);
        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->update('auth_item', ['type' => 2], ['name' => 'ATTACH_GALLERY_ADMINISTRATOR']);
        $this->update('auth_item', ['type' => 2], ['name' =>  'ATTACH_GALLERY_READER' ]);


        return true;
    }
}
