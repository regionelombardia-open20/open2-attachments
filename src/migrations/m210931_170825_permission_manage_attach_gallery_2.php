<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m200709_172825_permission_workflow_territory
 */
class m210931_170825_permission_manage_attach_gallery_2 extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            [
                'name' => \open20\amos\attachments\widgets\icons\WidgetIconGalleryImageRequestOpened::className(),
                'update' => true,
                'newValues' => [
                    'addParents' => ['MANAGE_ATTACH_GALLERY']
                ]
            ],
            [
                'name' => \open20\amos\attachments\widgets\icons\WidgetIconGalleryImageRequestClosed::className(),
                'update' => true,
                'newValues' => [
                    'addParents' => ['MANAGE_ATTACH_GALLERY']
                ]
            ],

        ];
    }
}