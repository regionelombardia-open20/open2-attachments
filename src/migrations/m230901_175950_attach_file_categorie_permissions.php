<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    package
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;

/**
 * Class m230901_175950_attach_file_categorie_permissions
 */
class m230901_175950_attach_file_categorie_permissions extends AmosMigrationPermissions
{
    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'ATTACHFILECATEGORIE_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model AttachFileCategorie',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' => 'ATTACHFILECATEGORIE_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model AttachFileCategorie',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' => 'ATTACHFILECATEGORIE_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model AttachFileCategorie',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' => 'ATTACHFILECATEGORIE_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model AttachFileCategorie',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
        ];
    }
}