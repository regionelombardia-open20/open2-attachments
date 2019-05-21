<?php
use lispa\amos\core\migration\AmosMigrationWidgets;
use lispa\amos\dashboard\models\AmosWidgets;


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
                'classname' => \lispa\amos\attachments\widgets\icons\WidgetIconGalleryDashboard::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'dashboard_visible' => 0,
                'default_order' => 70,
                'child_of' =>  \lispa\amos\dashboard\widgets\icons\WidgetIconManagement::className(),
            ],
            [
                'classname' => \lispa\amos\attachments\widgets\icons\WidgetIconGallery::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'dashboard_visible' => 0,
                'default_order' => 10,
                'child_of' =>  \lispa\amos\attachments\widgets\icons\WidgetIconGalleryDashboard::className(),
            ],
            [
                'classname' => \lispa\amos\attachments\widgets\icons\WidgetIconCategory::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'dashboard_visible' => 0,
                'default_order' => 20,
                'child_of' =>  \lispa\amos\attachments\widgets\icons\WidgetIconGalleryDashboard::className(),
            ],

        ];
    }
}
