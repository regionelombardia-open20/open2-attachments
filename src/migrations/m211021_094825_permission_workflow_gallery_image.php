<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m200709_172825_permission_workflow_territory
 */
class m211021_094825_permission_workflow_gallery_image extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            [
                'name' => 'ATTACH_GALLERY_OPERATOR',
                'type' => Permission::TYPE_ROLE,
                'description' => $prefixStr . 'Permesso di gestire la galleria',
                'ruleName' => null,
                'children' => [
                    'ATTACHGALLERY_READ',
                    'ATTACHGALLERYIMAGE_READ',
                    'ATTACHGALLERYREQUEST_CREATE', 'ATTACHGALLERYREQUEST_READ', 'ATTACHGALLERYREQUEST_UPDATE',
                ]
            ],

            [
                'name' => \open20\amos\attachments\widgets\icons\WidgetIconGalleryImageRequestMyRequests::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'Permission widget opened',
                'ruleName' => null,
                'parent' => ['ATTACH_GALLERY_OPERATOR'],
            ],

            //

            [
                'name' => \open20\amos\attachments\widgets\icons\WidgetIconSingleGallery::className(),
                'update' => true,
                'newValues' => [
                    'addParents' => ['ATTACH_GALLERY_OPERATOR']
                ]
            ],
            [
                'name' => \open20\amos\attachments\widgets\icons\WidgetIconGalleryDashboard::className(),
                'update' => true,
                'newValues' => [
                    'addParents' => ['ATTACH_GALLERY_OPERATOR']
                ]
            ],
            [
                'name' => 'ATTACHGALLERYREQUEST_CREATE',
                'update' => true,
                'newValues' => [
                    'addParents' => ['ATTACH_GALLERY_OPERATOR']
                ]
            ],
            [
                'name' => 'ATTACHGALLERYREQUEST_READ',
                'update' => true,
                'newValues' => [
                    'addParents' => ['ATTACH_GALLERY_OPERATOR']
                ]
            ],
            [
                'name' => \open20\amos\attachments\widgets\icons\WidgetIconGalleryImageRequestOpened::className(),
                'update' => true,
                'newValues' => [
                    'addParents' => ['ATTACH_GALLERY_OPERATOR']
                ]
            ],
            [
                'name' => \open20\amos\attachments\widgets\icons\WidgetIconGalleryImageRequestClosed::className(),
                'update' => true,
                'newValues' => [
                    'addParents' => ['ATTACH_GALLERY_OPERATOR']
                ]
            ],

        ];
    }
}