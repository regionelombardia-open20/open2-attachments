<?php
use open20\amos\core\migration\AmosMigrationWidgets;
use open20\amos\dashboard\models\AmosWidgets;


/**
* Class m180327_162827_add_amos_widgets_een_archived*/
class m181130_182127_add_widgets_gallery extends AmosMigrationWidgets
{
    const MODULE_NAME = 'attachments';

    /**
    * @inheritdoc
    */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => \open20\amos\attachments\widgets\icons\WidgetIconGalleryDashboard::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'dashboard_visible' => 0,
                'default_order' => 70,
                'child_of' =>  \open20\amos\dashboard\widgets\icons\WidgetIconManagement::className(),
            ],
            [
                'classname' => \open20\amos\attachments\widgets\icons\WidgetIconGallery::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'dashboard_visible' => 0,
                'default_order' => 10,
                'child_of' =>  \open20\amos\attachments\widgets\icons\WidgetIconGalleryDashboard::className(),
            ],
            [
                'classname' => \open20\amos\attachments\widgets\icons\WidgetIconCategory::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'dashboard_visible' => 0,
                'default_order' => 20,
                'child_of' =>  \open20\amos\attachments\widgets\icons\WidgetIconGalleryDashboard::className(),
            ],

        ];
    }
}
