<?php
/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 * @package   open20\amos-attachments
 * @author    Aria S.p.A. <protocollo@pec.ariaspa.it>
 * @license   GNU General Public License v3.0 <https://www.gnu.org/licenses/gpl-3.0.html>
 * @link      https://www.ariaspa.it/
 *
 * This file is part of OPEN 2.0.
 *
 * OPEN 2.0 is free software: you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * OPEN 2.0 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OPEN 2.0. If not, see <https://www.gnu.org/licenses/>.
 */

use open20\amos\core\migration\AmosMigrationPermissions;
use yii\rbac\Permission;


/**
* Class m181129_105016_attach_gallery_category_permissions*/
class m181129_105016_attach_gallery_permissions extends AmosMigrationPermissions
{

    /**
    * @inheritdoc
    */
    protected function setRBACConfigurations()
    {
        $prefixStr = '';

        return [
                [
                    'name' =>  'ATTACH_GALLERY_ADMINISTRATOR',
                    'type' => Permission::TYPE_ROLE,
                    'description' => 'Permesso di CREATE sul model AttachGalleryCategory',
                    'ruleName' => null,
                    'parent' => ['ADMIN']
                ],
                [
                    'name' =>  'ATTACH_GALLERY_READER',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di CREATE sul model AttachGalleryCategory',
                    'ruleName' => null,
                    'parent' => ['BASIC_USER']
                ],
                [
                    'name' =>  'ATTACHGALLERYCATEGORY_CREATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di CREATE sul model AttachGalleryCategory',
                    'ruleName' => null,
                    'parent' => ['ATTACH_GALLERY_ADMINISTRATOR']
                ],
                [
                    'name' =>  'ATTACHGALLERYCATEGORY_READ',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di READ sul model AttachGalleryCategory',
                    'ruleName' => null,
                    'parent' => ['ATTACH_GALLERY_ADMINISTRATOR','ATTACH_GALLERY_READER']
                    ],
                [
                    'name' =>  'ATTACHGALLERYCATEGORY_UPDATE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di UPDATE sul model AttachGalleryCategory',
                    'ruleName' => null,
                    'parent' => ['ATTACH_GALLERY_ADMINISTRATOR']
                ],
                [
                    'name' =>  'ATTACHGALLERYCATEGORY_DELETE',
                    'type' => Permission::TYPE_PERMISSION,
                    'description' => 'Permesso di DELETE sul model AttachGalleryCategory',
                    'ruleName' => null,
                    'parent' => ['ATTACH_GALLERY_ADMINISTRATOR']
                ],
            // ----------------
            [
                'name' =>  'ATTACHGALLERY_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model AttachGallery',
                'ruleName' => null,
                'parent' => ['ATTACH_GALLERY_ADMINISTRATOR']
            ],
            [
                'name' =>  'ATTACHGALLERY_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model AttachGallery',
                'ruleName' => null,
                'parent' => ['ATTACH_GALLERY_ADMINISTRATOR', 'ATTACH_GALLERY_READER']
            ],
            [
                'name' =>  'ATTACHGALLERY_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model AttachGallery',
                'ruleName' => null,
                'parent' => ['ATTACH_GALLERY_ADMINISTRATOR']
            ],
            [
                'name' =>  'ATTACHGALLERY_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model AttachGallery',
                'ruleName' => null,
                'parent' => ['ATTACH_GALLERY_ADMINISTRATOR']
            ],
            // -----------------
            [
                'name' =>  'ATTACHGALLERYIMAGE_CREATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di CREATE sul model AttachGalleryImage',
                'ruleName' => null,
                'parent' => ['ATTACH_GALLERY_ADMINISTRATOR']
            ],
            [
                'name' =>  'ATTACHGALLERYIMAGE_READ',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di READ sul model AttachGalleryImage',
                'ruleName' => null,
                'parent' => ['ATTACH_GALLERY_ADMINISTRATOR','ATTACH_GALLERY_READER']
            ],
            [
                'name' =>  'ATTACHGALLERYIMAGE_UPDATE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di UPDATE sul model AttachGalleryImage',
                'ruleName' => null,
                'parent' => ['ATTACH_GALLERY_ADMINISTRATOR']
            ],
            [
                'name' =>  'ATTACHGALLERYIMAGE_DELETE',
                'type' => Permission::TYPE_PERMISSION,
                'description' => 'Permesso di DELETE sul model AttachGalleryImage',
                'ruleName' => null,
                'parent' => ['ATTACH_GALLERY_ADMINISTRATOR']
            ],

            ];
    }
}
