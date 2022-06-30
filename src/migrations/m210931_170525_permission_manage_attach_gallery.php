<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m200709_172825_permission_workflow_territory
 */
class m210931_170525_permission_manage_attach_gallery extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            [
                'name' => \open20\amos\attachments\widgets\icons\WidgetIconSingleGallery::className(),
                'update' => true,
                'newValues' => [
                    'addParents' => ['MANAGE_ATTACH_GALLERY', 'ATTACH_IMAGE_REQUEST_OPERATOR']
                ]
            ],
            [
                'name' => \open20\amos\attachments\widgets\icons\WidgetIconGalleryDashboard::className(),
                'update' => true,
                'newValues' => [
                    'addParents' => ['MANAGE_ATTACH_GALLERY', 'ATTACH_IMAGE_REQUEST_OPERATOR']
                ]
            ],
            [
                'name' => 'ATTACHGALLERYREQUEST_CREATE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['MANAGE_ATTACH_GALLERY', 'ATTACH_IMAGE_REQUEST_OPERATOR']
                ]
            ],
            [
                'name' => 'ATTACHGALLERYREQUEST_READ',
                'update' => true,
                'newValues' => [
                    'addParents' => ['MANAGE_ATTACH_GALLERY', 'ATTACH_IMAGE_REQUEST_OPERATOR']
                ]
            ],
            [
                'name' => 'ATTACHGALLERYREQUEST_UPDATE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['ATTACH_IMAGE_REQUEST_OPERATOR']
                ]
            ],
            [
                'name' => 'ATTACHGALLERY_READ',
                'update' => true,
                'newValues' => [
                    'addParents' => ['ATTACH_IMAGE_REQUEST_OPERATOR']
                ]
            ],
            [
                'name' => 'ATTACHGALLERYIMAGE_READ',
                'update' => true,
                'newValues' => [
                    'addParents' => ['ATTACH_IMAGE_REQUEST_OPERATOR']
                ]
            ],

        ];
    }
}