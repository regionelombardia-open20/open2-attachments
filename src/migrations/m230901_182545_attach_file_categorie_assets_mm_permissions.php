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
 * Class m230901_182545_attach_file_categorie_assets_mm_permissions
 */
class m230901_182545_attach_file_categorie_assets_mm_permissions extends AmosMigrationPermissions
{

    /**
     * @inheritdoc
     */
    protected function setRBACConfigurations()
    {
        return [
            [
                'name' => 'ATTACHFILECATEGORIEASSETSMM_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model AttachFileCategorieAssetsMm',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' => 'ATTACHFILECATEGORIEASSETSMM_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model AttachFileCategorieAssetsMm',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' => 'ATTACHFILECATEGORIEASSETSMM_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model AttachFileCategorieAssetsMm',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
            [
                'name' => 'ATTACHFILECATEGORIEASSETSMM_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model AttachFileCategorieAssetsMm',
                'ruleName' => null,
                'parent' => ['ADMIN']
            ],
        ];
    }
}