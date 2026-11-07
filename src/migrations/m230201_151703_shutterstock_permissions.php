<?php

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
 * Class m230114_151703_attach_databank_file_permissions*/
class m230201_151703_shutterstock_permissions extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
            [
                'name' => 'MANAGE_ATTACH_SHUTTERSTOCK',
                'type' => Permission::TYPE_ROLE,
                'description' => 'Amministratore shutterstock',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],

        ];
    }
}
