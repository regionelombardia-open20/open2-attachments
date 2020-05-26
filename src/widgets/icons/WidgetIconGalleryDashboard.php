<?php

/**
 * Aria S.p.A.
 * OPEN 2.0
 *
 *
 * @package    amos\sitemanagement\widgets\icons
 * @category   CategoryName
 */

namespace open20\amos\attachments\widgets\icons;

use open20\amos\attachments\FileModule;
use open20\amos\core\widget\WidgetIcon;
use yii\helpers\ArrayHelper;

/**
 * Class WidgetIconSiteManagementSlider
 * @package amos\sitemanagement\widgets\icons
 */
class WidgetIconGalleryDashboard extends WidgetIcon
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $module = \Yii::$app->getModule('attachments');
        $this->setLabel(FileModule::tHtml('amosattachments', '#label_gallery_image'));
        $this->setDescription(FileModule::t('amosattachments', '#desc_gallery_image'));
        $this->setIcon('linentita');

        if ($module->enableSingleGallery) {
            $this->setUrl(['/attachments/attach-gallery/single-gallery']);
        } else {
            $this->setUrl(['/attachments/attach-gallery']);
        }

        $this->setCode('ATTACHMENTS_GALLERY');
        $this->setModuleName('attachments');
        $this->setNamespace(__CLASS__);

        $this->setClassSpan(
            ArrayHelper::merge(
                $this->getClassSpan(),
                [
                    'bk-backgroundIcon',
                    'color-lightPrimary'
                ]
            )
        );
    }

}
