<?php

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
