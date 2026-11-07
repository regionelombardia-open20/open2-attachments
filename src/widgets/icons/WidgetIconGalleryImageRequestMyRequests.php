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
class WidgetIconGalleryImageRequestMyRequests extends WidgetIcon
{

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->setLabel(FileModule::tHtml('amosattachments', 'Le mie richieste'));
        $this->setDescription(FileModule::t('amosattachments', 'Le mie richieste'));
        $this->setIcon('linentita');
        $this->setUrl(['/attachments/attach-gallery-request/my-requests']);
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
