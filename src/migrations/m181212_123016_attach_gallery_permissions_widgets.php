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
