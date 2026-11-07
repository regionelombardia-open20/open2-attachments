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
class m230131_100327_add_field_attach_gallery_image extends Migration
{


    /**
     * @inheritdoc
     */
    public function safeUp(){
        $this->addColumn('attach_gallery_image', 'shutterstock_image_id', $this->string()->after('aspect_ratio'));
    }



    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropColumn('attach_gallery_image', 'shutterstock_image_id');

    }
}
