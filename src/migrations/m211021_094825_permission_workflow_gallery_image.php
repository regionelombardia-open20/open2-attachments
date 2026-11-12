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