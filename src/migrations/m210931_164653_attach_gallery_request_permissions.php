<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m210901_164653_attach_gallery_request_permissions*/
class m210931_164653_attach_gallery_request_permissions extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
                [
                    'name' =>  'ATTACHGALLERYREQUEST_CREATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di CREATE sul model AttachGalleryRequest',
                    'ruleName' => null,
                    'parent' => ['ATTACH_GALLERY_ADMINISTRATOR']
                ],
                [
                    'name' =>  'ATTACHGALLERYREQUEST_READ',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di READ sul model AttachGalleryRequest',
                    'ruleName' => null,
                    'parent' => ['ATTACH_GALLERY_ADMINISTRATOR']
                    ],
                [
                    'name' =>  'ATTACHGALLERYREQUEST_UPDATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di UPDATE sul model AttachGalleryRequest',
                    'ruleName' => null,
                    'parent' => ['ATTACH_GALLERY_ADMINISTRATOR']
                ],
                [
                    'name' =>  'ATTACHGALLERYREQUEST_DELETE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di DELETE sul model AttachGalleryRequest',
                    'ruleName' => null,
                    'parent' => ['ATTACH_GALLERY_ADMINISTRATOR']
                ],

            ];
    }
}
