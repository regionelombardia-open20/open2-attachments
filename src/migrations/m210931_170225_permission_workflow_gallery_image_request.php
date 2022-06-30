<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m200709_172825_permission_workflow_territory
 */
class m210931_170225_permission_workflow_gallery_image_request extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            [
                'name' => 'MANAGE_ATTACH_GALLERY',
                'type' => Permission::TYPE_ROLE,
                'description' => $prefixStr . 'Permesso di gestire la galleria',
                'ruleName' => null,
                'parent' => ['ATTACH_GALLERY_ADMINISTRATOR'],
                'children'=> [
                    'ATTACHGALLERY_CREATE', 'ATTACHGALLERY_READ', 'ATTACHGALLERY_UPDATE', 'ATTACHGALLERY_DELETE',
                    'ATTACHGALLERYIMAGE_CREATE', 'ATTACHGALLERYIMAGE_READ', 'ATTACHGALLERYIMAGE_UPDATE', 'ATTACHGALLERYIMAGE_DELETE',
                    'ATTACHGALLERYCATEGORY_CREATE', 'ATTACHGALLERYCATEGORY_READ', 'ATTACHGALLERYCATEGORY_UPDATE', 'ATTACHGALLERYCATEGORY_DELETE',
                    'ATTACHGALLERYREQUEST_CREATE', 'ATTACHGALLERYREQUEST_READ', 'ATTACHGALLERYREQUEST_UPDATE',
                ]
            ],
            [
                'name' => 'ATTACH_IMAGE_REQUEST_OPERATOR',
                'type' => Permission::TYPE_ROLE,
                'description' => $prefixStr . 'Permsso per rispondere alle richieste',
                'ruleName' => null,
                'parent' => ['ATTACH_GALLERY_ADMINISTRATOR'],
            ],
            [
                'name' => \open20\amos\attachments\models\AttachGalleryRequest::IMAGE_REQUEST_WORKFLOW_STATUS_CLOSED,
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'Permission publish technology',
                'ruleName' => null,
                'parent' => ['ATTACH_IMAGE_REQUEST_OPERATOR'],
            ],
            [
                'name' => \open20\amos\attachments\widgets\icons\WidgetIconGalleryImageRequestOpened::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'Permission widget opened',
                'ruleName' => null,
                'parent' => ['ATTACH_IMAGE_REQUEST_OPERATOR'],
            ],
            [
                'name' => \open20\amos\attachments\widgets\icons\WidgetIconGalleryImageRequestClosed::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'Permission widget opened',
                'ruleName' => null,
                'parent' => ['ATTACH_IMAGE_REQUEST_OPERATOR'],
            ],
            [
                'name' => \open20\amos\attachments\widgets\icons\WidgetIconGalleryImageRequestMyRequests::className(),
                'type' => Permission::TYPE_PERMISSION,
                'description' => $prefixStr . 'Permission widget opened',
                'ruleName' => null,
                'parent' => ['MANAGE_ATTACH_GALLERY'],
            ],
            [
                'name' => \open20\amos\attachments\widgets\icons\WidgetIconCategory::className(),
                'update' => true,
                'newValues' => [
                    'removeParents' => ['ATTACH_GALLERY_ADMINISTRATOR']
                ]
            ]

        ];
    }
}