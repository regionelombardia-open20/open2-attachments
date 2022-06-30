<?php
use open20\amos\core\migration\AmosMigrationWidgets;
use open20\amos\dashboard\models\AmosWidgets;


/**
* Class m180327_162827_add_amos_widgets_een_archived*/
class m181212_122042_add_widgets_single_gallery extends AmosMigrationWidgets
{
    const MODULE_NAME = 'attachments';

    /**
    * @inheritdoc
    */
    protected function initWidgetsConfs()
    {
        $this->widgets = [
            [
                'classname' => \open20\amos\attachments\widgets\icons\WidgetIconSingleGallery::className(),
                'type' => AmosWidgets::TYPE_ICON,
                'module' => self::MODULE_NAME,
                'status' => AmosWidgets::STATUS_ENABLED,
                'dashboard_visible' => 0,
                'default_order' => 10,
                'child_of' =>  \open20\amos\attachments\widgets\icons\WidgetIconGalleryDashboard::className(),
            ],

        ];
    }
}
