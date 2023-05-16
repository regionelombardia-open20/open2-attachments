<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
 * Class m230114_151703_attach_databank_file_permissions*/
class m230114_151703_attach_databank_file_permissions extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            [
                'name' => 'ATTACH_DATABANK_FILE_ADMINISTRATOR',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Amministratore databank file',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' => 'ATTACHDATABANKFILE_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model AttachDatabankFile',
                'ruleName' => null,
                'parent' => ['ATTACH_GALLERY_ADMINISTRATOR']
            ],
            [
                'name' => 'ATTACHDATABANKFILE_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model AttachDatabankFile',
                'ruleName' => null,
                'parent' => ['ATTACH_GALLERY_ADMINISTRATOR']
            ],
            [
                'name' => 'ATTACHDATABANKFILE_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model AttachDatabankFile',
                'ruleName' => null,
                'parent' => ['ATTACH_GALLERY_ADMINISTRATOR']
            ],
            [
                'name' => 'ATTACHDATABANKFILE_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model AttachDatabankFile',
                'ruleName' => null,
                'parent' => ['ATTACH_GALLERY_ADMINISTRATOR']
            ],

        ];
    }
}
