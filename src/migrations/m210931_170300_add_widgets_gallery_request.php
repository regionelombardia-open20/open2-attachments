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

use open20\amos\core\migration\AmosMigrationWidgets;
use open20\amos\dashboard\models\AmosWidgets;
use open20\amos\dashboard\utility\DashboardUtility;

class m210931_170300_add_widgets_gallery_request extends AmosMigrationWidgets
{
    /**
     * @inheritdoc
     */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => \open20\amos\attachments\widgets\icons\WidgetIconGalleryImageRequestMyRequests::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => 'attachments',
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \open20\amos\attachments\widgets\icons\WidgetIconGalleryDashboard::className(),
                'default_order' => 11,
                'dashboard_visible' => 0,
            ],
            [
                'classname' => \open20\amos\attachments\widgets\icons\WidgetIconGalleryImageRequestOpened::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => 'attachments',
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \open20\amos\attachments\widgets\icons\WidgetIconGalleryDashboard::className(),
                'default_order' => 12,
                'dashboard_visible' => 0,
            ],
            [
                'classname' => \open20\amos\attachments\widgets\icons\WidgetIconGalleryImageRequestClosed::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => 'attachments',
                'status' => AmosWidgets::STATUS_ENABLED,
                'child_of' => \open20\amos\attachments\widgets\icons\WidgetIconGalleryDashboard::className(),
                'default_order' => 13,
                'dashboard_visible' => 0,
            ],
        ];
    }
}
