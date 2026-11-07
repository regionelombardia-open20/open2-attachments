<?php
use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m181129_105016_attach_gallery_category_permissions*/
class m181212_123016_attach_gallery_permissions_widgets extends AmosMigrationPermissions
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
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso widget Gallery dashboard',
                    'ruleName' => null,
                    'parent' => ['ATTACH_GALLERY_ADMINISTRATOR']
                ],
                [
                    'name' => \open20\amos\attachments\widgets\icons\WidgetIconGallery::className(),
                    'update' => true,
                    'newValues' => ['removeParents' => ['ATTACH_GALLERY_ADMINISTRATOR']]
                ],
            ];
    }
}
